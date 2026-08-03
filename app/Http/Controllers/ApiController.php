<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\Practice;
use App\Models\PredefinedIncidence;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get all public data for React frontend (Groups, Rankings, Statistics, Incidences)
     */
    public function getPublicData(Request $request)
    {
        $groups = ClassGroup::where('is_deleted', false)->get();
        
        if ($request->has('group_id') && $request->get('group_id') !== null && $request->get('group_id') !== '') {
            $requestedGroupId = $request->get('group_id');
            $currentGroup = ClassGroup::where('id', $requestedGroupId)->where('is_deleted', false)->first();

            if (!$currentGroup) {
                return response()->json([
                    'success' => false,
                    'error' => 'group_not_found',
                    'message' => 'El grupo especificado con ID "' . $requestedGroupId . '" no fue encontrado o ha sido eliminado.',
                    // 'groups' => $groups->map(function ($g) {
                    //     return [
                    //         'id' => (string) $g->id,
                    //         'name' => $g->name,
                    //         'subject' => $g->subject,
                    //     ];
                    // })
                ], 404);
            }
            $selectedGroupId = $currentGroup->id;
        } else {
            $currentGroup = $groups->first();
            $selectedGroupId = $currentGroup?->id;
        }

        $students = Student::where('class_group_id', $selectedGroupId)
            ->where('is_deleted', false)
            ->with(['incidences', 'gradeHistories'])
            ->get();

        // Format calculated students for React matching calculations.js
        $calculatedStudents = $students->map(function ($student) {
            // $pos = $student->total_positive_points;
            // $neg = $student->total_negative_points;
            // $net = $student->net_incidence_points;
            // $computedBase = $student->computed_base_grade;
            // $rawScore = $student->raw_score;
            // $finalScore = $student->final_score;
            // $exceedsTen = $student->exceeds_ten;
            // $extraPoints = $student->extra_points;
            // $finalGrade = $student->raw_score > 10.0 ? 10 : (int) round($finalScore);

            return [
                'id' => (string) $student->id,
                'name' => $student->name,
                'avatar' => $student->avatar,
                'gender' => $student->gender,
                'classGroupId' => (string) $student->class_group_id,
                // 'baseGrade' => (float) $computedBase,
                'examGrade' => (float) ($student->exam_grade ?? 0),
                'evaluationGrades' => $student->evaluation_grades ?? (object)[],
                // 'totalPositivePoints' => (float) $pos,
                // 'totalNegativePoints' => (float) $neg,
                // 'netIncidencePoints' => (float) $net,
                // 'rawScore' => (float) $rawScore,
                // 'extraPoints' => (float) $extraPoints,
                // 'finalScore' => (float) $finalScore,
                // 'finalGrade' => $finalGrade,
                // 'exceedsTen' => $exceedsTen,
                'incidences' => $student->incidences->map(function ($inc) {
                    return [
                        'id' => (string) $inc->id,
                        'type' => $inc->type,
                        'title' => $inc->title,
                        'points' => (float) $inc->points,
                        'category' => $inc->category,
                        'note' => $inc->note,
                        'date' => $inc->date ? $inc->date->toIso8601String() : now()->toIso8601String(),
                    ];
                }),
                // 'gradeHistories' => $student->gradeHistories->map(function ($gh) {
                //     return [
                //         'id' => (string) $gh->id,
                //         'evaluationName' => $gh->evaluation_name,
                //         'oldScore' => $gh->old_score !== null ? (float) $gh->old_score : null,
                //         'newScore' => (float) $gh->new_score,
                //         'note' => $gh->note,
                //         'date' => $gh->date ? $gh->date->toIso8601String() : now()->toIso8601String(),
                //     ];
                // }),
            ];
        });

        // Sort descending: finalScore desc, netIncidencePoints desc, totalPositivePoints desc, baseGrade desc, name asc
        // $sorted = $calculatedStudents->sort(function ($a, $b) {
        //     if (abs($b['finalScore'] - $a['finalScore']) > 0.01) {
        //         return $b['finalScore'] <=> $a['finalScore'];
        //     }
        //     if (abs($b['netIncidencePoints'] - $a['netIncidencePoints']) > 0.01) {
        //         return $b['netIncidencePoints'] <=> $a['netIncidencePoints'];
        //     }
        //     if (abs($b['totalPositivePoints'] - $a['totalPositivePoints']) > 0.01) {
        //         return $b['totalPositivePoints'] <=> $a['totalPositivePoints'];
        //     }
        //     if (abs($b['baseGrade'] - $a['baseGrade']) > 0.01) {
        //         return $b['baseGrade'] <=> $a['baseGrade'];
        //     }
        //     return strcasecmp($a['name'], $b['name']);
        // })->values();

        // Assign ranks and badges
        // $baseGradeCounts = [];
        // foreach ($sorted as $item) {
        //     $key = (string) $item['baseGrade'];
        //     $baseGradeCounts[$key] = ($baseGradeCounts[$key] ?? 0) + 1;
        // }

        // $rankedStudents = $sorted->map(function ($item, $index) use ($baseGradeCounts) {
        //     $rank = $index + 1;
        //     $key = (string) $item['baseGrade'];
        //     $isTiedInBase = ($baseGradeCounts[$key] ?? 0) > 1;

        //     $badgeText = '';
        //     if ($item['exceedsTen']) {
        //         $badgeText = 'Supera el 10 (+' . ($item['netIncidencePoints'] > 0 ? $item['netIncidencePoints'] : 0) . 'pts)';
        //     } elseif ($isTiedInBase && $item['netIncidencePoints'] > 0) {
        //         $badgeText = 'Desempate (' . $item['baseGrade'] . ' + ' . $item['netIncidencePoints'] . 'pts)';
        //     } elseif ($item['netIncidencePoints'] < 0) {
        //         $badgeText = 'Penalizado (' . $item['netIncidencePoints'] . 'pts)';
        //     }

        //     $item['rank'] = $rank;
        //     $item['isTiedInBaseGrade'] = $isTiedInBase;
        //     $item['badgeText'] = $badgeText;

        //     return $item;
        // });

        return response()->json([
            'success' => true,
            // 'groups' => $groups->map(function ($g) {
            //     return [
            //         'id' => (string) $g->id,
            //         'name' => $g->name,
            //         'subject' => $g->subject,
            //         'gradeLevel' => $g->grade_level,
            //         'academicYear' => $g->academic_year,
            //         'totalPractices' => $g->total_practices,
            //         'totalWeeks' => $g->total_weeks,
            //         'currentWeek' => $g->current_week,
            //         'weekStatus' => $g->week_status,
            //         'evaluations' => $g->practices->map(function ($p) {
            //             return [
            //                 'id' => 'pr-' . $p->id,
            //                 'name' => $p->name,
            //                 'weight' => $p->weight,
            //             ];
            //         })->values(),
            //     ];
            // }),
            'currentGroup' => $currentGroup ? [
                'id' => (string) $currentGroup->id,
                'name' => $currentGroup->name,
                'subject' => $currentGroup->subject,
                'gradeLevel' => $currentGroup->grade_level,
                'academicYear' => $currentGroup->academic_year,
                'totalPractices' => $currentGroup->total_practices,
                'totalWeeks' => $currentGroup->total_weeks,
                'currentWeek' => $currentGroup->current_week,
                'weekStatus' => $currentGroup->week_status,
                // 'evaluations' => $currentGroup->practices->map(function ($p) {
                //     return [
                //         'id' => 'pr-' . $p->id,
                //         'name' => $p->name,
                //         'weight' => $p->weight,
                //     ];
                // })->values(),
            ] : null,
            // 'predefinedIncidences' => PredefinedIncidence::all(),
            // 'students' => $rankedStudents,
            'students' => $calculatedStudents,
            'stats' => [
                // 'totalStudents' => $rankedStudents->count(),
                'totalStudents' => $calculatedStudents->count(),
                // 'averageGrade' => round($rankedStudents->avg('finalScore') ?? 0, 1),
                // 'averageGrade' => round($calculatedStudents->avg('finalScore') ?? 0, 1),
                // 'totalPositiveIncidences' => $rankedStudents->sum('totalPositivePoints'),
                // 'totalNegativeIncidences' => $rankedStudents->sum('totalNegativePoints'),
            ]
        ]);
    }
}
