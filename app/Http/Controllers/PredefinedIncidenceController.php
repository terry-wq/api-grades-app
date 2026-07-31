<?php

namespace App\Http\Controllers;

use App\Models\PredefinedIncidence;
use Illuminate\Http\Request;

class PredefinedIncidenceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:positive,negative',
            'points' => 'required|numeric|min:0.1',
            'category' => 'required|string|max:255',
        ]);

        PredefinedIncidence::create($validated);

        return redirect()->back()->with('success', 'Plantilla de incidencia predefinida guardada.');
    }

    public function destroy($id)
    {
        $item = PredefinedIncidence::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Plantilla de incidencia eliminada.');
    }
}
