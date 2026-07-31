<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\Student;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_group_id' => 'required|exists:class_groups,id',
            'name' => 'required|string|max:255',
            'weight' => 'nullable|numeric|min:0.1',
        ]);

        $practice = Practice::create($validated);

        // Assign this new practice to all existing students in the group with 0.0 initial grade
        $students = Student::where('class_group_id', $validated['class_group_id'])
            ->where('is_deleted', false)
            ->get();

        $evalKey = 'pr-' . $practice->id;
        foreach ($students as $student) {
            $evals = $student->evaluation_grades ?? [];
            if (!array_key_exists($evalKey, $evals)) {
                $evals[$evalKey] = 0.0;
                $student->evaluation_grades = $evals;
                $student->save();
            }
        }

        return redirect()->back()->with('success', 'Práctica agregada y asignada a todos los alumnos del grupo.');
    }
}
