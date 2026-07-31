<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Student;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function exportCsv($groupId)
    {
        $group = ClassGroup::with('students.incidences')->findOrFail($groupId);

        $filename = "Reporte_Alumnos_" . str_replace(' ', '_', $group->name) . "_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($group) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'ID', 'Nombre', 'Grupo', 'Materia', 'Calificacion Base', 
                'Puntos Positivos', 'Puntos Negativos', 'Puntos Netos Incidencias', 'Calificacion Final'
            ]);

            foreach ($group->students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $group->name,
                    $group->subject,
                    $student->base_grade,
                    $student->total_positive_points,
                    $student->total_negative_points,
                    $student->net_incidence_points,
                    $student->final_score,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
