<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\Student;
use Illuminate\Http\Request;

class IncidenceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:positive,negative',
            'title' => 'required|string|max:255',
            'points' => 'required|numeric|min:0.1',
            'category' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $validated['date'] = now();

        Incidence::create($validated);

        return redirect()->back()->with('success', 'Incidencia registrada exitosamente.');
    }

    public function destroy($id)
    {
        $incidence = Incidence::findOrFail($id);
        $incidence->delete();

        return redirect()->back()->with('success', 'Incidencia eliminada.');
    }
}
