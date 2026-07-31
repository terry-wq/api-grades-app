<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Practice;
use App\Models\GradeHistory;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Store a new student in a group.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_group_id' => 'required|exists:class_groups,id',
            'name' => 'required|string|max:255',
            'base_grade' => 'required|numeric|min:0|max:10',
            'gender' => 'nullable|string|in:M,F,O',
            'avatar' => 'nullable|string|url',
        ]);

        $gender = $request->gender ?? 'M';
        $defaultAvatar = $request->avatar ?: "https://api.dicebear.com/7.x/bottts/svg?seed=" . urlencode($request->name);

        // Pre-populate evaluation grades for all existing group practices with default 0.0
        $practices = Practice::where('class_group_id', $request->class_group_id)->get();
        $evalGrades = [];
        foreach ($practices as $p) {
            $evalGrades['pr-' . $p->id] = 0.0;
        }

        $student = Student::create([
            'class_group_id' => $request->class_group_id,
            'name' => $request->name,
            'base_grade' => $request->base_grade,
            'gender' => $gender,
            'avatar' => $defaultAvatar,
            'evaluation_grades' => $evalGrades,
        ]);

        return redirect()->back()->with('success', "Alumno {$student->name} agregado correctamente.");
    }

    /**
     * Update student evaluation/practice grades & exam grade.
     */
    public function updateEvaluationGrades(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'exam_grade' => 'nullable|numeric|min:0|max:10',
            'evaluation_grades' => 'nullable|array',
            'evaluation_grades.*' => 'nullable|numeric|min:0|max:10',
        ]);

        if ($request->has('exam_grade')) {
            $student->exam_grade = (float) $request->exam_grade;
        }

        if ($request->has('evaluation_grades')) {
            $current = $student->evaluation_grades ?? [];
            foreach ($request->evaluation_grades as $key => $val) {
                if ($val !== null) {
                    $current[$key] = (float) $val;
                }
            }
            $student->evaluation_grades = $current;
        }

        // Recalculate base_grade from practices & exam grade
        $student->base_grade = $student->computed_base_grade;
        $student->save();

        return redirect()->back()->with('success', "Calificaciones de {$student->name} actualizadas correctamente.");
    }

    /**
     * Update student avatar.
     */
    public function updateAvatar(Request $request, $id)
    {
        $request->validate([
            'avatar' => 'required|string|url',
        ]);

        $student = Student::findOrFail($id);
        $student->update(['avatar' => $request->avatar]);

        return redirect()->back()->with('success', "Avatar de {$student->name} actualizado.");
    }

    /**
     * Update student base grade and log history.
     */
    public function updateGrade(Request $request, $id)
    {
        $request->validate([
            'base_grade' => 'required|numeric|min:0|max:10',
            'evaluation_name' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $student = Student::findOrFail($id);
        $oldGrade = $student->base_grade;
        $student->update(['base_grade' => $request->base_grade]);

        // Record grade history
        GradeHistory::create([
            'student_id' => $student->id,
            'evaluation_name' => $request->evaluation_name ?? 'Calificación Base Ajustada',
            'old_score' => $oldGrade,
            'new_score' => $request->base_grade,
            'note' => $request->note ?? 'Ajuste por el profesor',
            'date' => now(),
        ]);

        return redirect()->back()->with('success', "Calificación de {$student->name} actualizada a {$request->base_grade}.");
    }

    /**
     * Delete student (soft delete flag).
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->update(['is_deleted' => true]);

        return redirect()->back()->with('success', "Alumno {$student->name} eliminado.");
    }
}
