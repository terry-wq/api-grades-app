<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\Practice;
use App\Models\PredefinedIncidence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard View (Public vs Authenticated)
     */
    public function index(Request $request)
    {
        $groups = ClassGroup::where('is_deleted', false)->get();
        $selectedGroupId = $request->get('group_id', $groups->first()?->id);
        
        $currentGroup = ClassGroup::find($selectedGroupId);

        if (!$currentGroup && $groups->count() > 0) {
            $currentGroup = $groups->first();
            $selectedGroupId = $currentGroup->id;
        }

        $students = Student::where('class_group_id', $selectedGroupId)
            ->where('is_deleted', false)
            ->with(['incidences', 'gradeHistories'])
            ->get();

        // Calculate scores and ranks matching calculations.js
        $calculatedStudents = $students->map(function ($student) {
            $pos = $student->total_positive_points;
            $neg = $student->total_negative_points;
            $net = $student->net_incidence_points;
            $computedBase = $student->computed_base_grade;
            $rawScore = $student->raw_score;
            $finalScore = $student->final_score;
            $exceedsTen = $student->exceeds_ten;
            $extraPoints = $student->extra_points;

            return [
                'student' => $student,
                'computedBaseGrade' => $computedBase,
                'totalPositivePoints' => $pos,
                'totalNegativePoints' => $neg,
                'netIncidencePoints' => $net,
                'rawScore' => $rawScore,
                'finalScore' => $finalScore,
                'extraPoints' => $extraPoints,
                'exceedsTen' => $exceedsTen,
            ];
        });

        // Sort: finalScore desc, netIncidencePoints desc, totalPositivePoints desc, computedBaseGrade desc, name asc
        $sorted = $calculatedStudents->sort(function ($a, $b) {
            if (abs($b['finalScore'] - $a['finalScore']) > 0.01) {
                return $b['finalScore'] <=> $a['finalScore'];
            }
            if (abs($b['netIncidencePoints'] - $a['netIncidencePoints']) > 0.01) {
                return $b['netIncidencePoints'] <=> $a['netIncidencePoints'];
            }
            if (abs($b['totalPositivePoints'] - $a['totalPositivePoints']) > 0.01) {
                return $b['totalPositivePoints'] <=> $a['totalPositivePoints'];
            }
            if (abs($b['computedBaseGrade'] - $a['computedBaseGrade']) > 0.01) {
                return $b['computedBaseGrade'] <=> $a['computedBaseGrade'];
            }
            return strcasecmp($a['student']->name, $b['student']->name);
        })->values();

        // Assign ranks and check ties/badges
        $baseGradeCounts = [];
        foreach ($sorted as $item) {
            $key = (string) $item['computedBaseGrade'];
            $baseGradeCounts[$key] = ($baseGradeCounts[$key] ?? 0) + 1;
        }

        $rankedStudents = $sorted->map(function ($item, $index) use ($baseGradeCounts) {
            $rank = $index + 1;
            $key = (string) $item['computedBaseGrade'];
            $isTiedInBase = ($baseGradeCounts[$key] ?? 0) > 1;

            $badgeText = '';
            if ($item['exceedsTen']) {
                $badgeText = 'Supera el 10 (+' . ($item['netIncidencePoints'] > 0 ? $item['netIncidencePoints'] : 0) . 'pts)';
            } elseif ($isTiedInBase && $item['netIncidencePoints'] > 0) {
                $badgeText = 'Desempate (' . $item['computedBaseGrade'] . ' + ' . $item['netIncidencePoints'] . 'pts)';
            } elseif ($item['netIncidencePoints'] < 0) {
                $badgeText = 'Penalizado (' . $item['netIncidencePoints'] . 'pts)';
            }

            $item['rank'] = $rank;
            $item['isTiedInBaseGrade'] = $isTiedInBase;
            $item['badgeText'] = $badgeText;

            return $item;
        });

        $isAuthenticated = auth()->check();

        $activeTab = $request->get('tab', 'podium');
        if (!in_array($activeTab, ['podium', 'list', 'stats'])) {
            $activeTab = 'podium';
        }

        $practices = $currentGroup ? Practice::where('class_group_id', $currentGroup->id)->get() : collect();
        $predefinedIncidences = PredefinedIncidence::all();

        return view('dashboard', [
            'groups' => $groups,
            'currentGroup' => $currentGroup,
            'rankedStudents' => $rankedStudents,
            'practices' => $practices,
            'predefinedIncidences' => $predefinedIncidences,
            'activeTab' => $activeTab,
            'isAuthenticated' => $isAuthenticated,
        ]);
    }

    public function updateGroupSettings(Request $request, $id)
    {
        $group = ClassGroup::findOrFail($id);
        
        $group->update($request->only([
            'name',
            'subject',
            'grade_level',
            'academic_year',
            'total_practices',
            'total_weeks',
            'current_week',
            'week_status',
        ]));

        return redirect()->back()->with('success', 'Configuración de grupo actualizada correctamente.');
    }
}
