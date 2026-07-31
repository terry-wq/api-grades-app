<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
       // Clear existing data to allow clean re-seeding
       GradeHistory::query()->delete();
       Incidence::query()->delete();
       Student::query()->delete();
       Practice::query()->delete();
       ClassGroup::query()->delete();
       PredefinedIncidence::query()->delete();

       // 0. Seed Predefined Incidences
       $predefined = [
           ['title' => 'Ganador de Olimpiada Académica', 'type' => 'positive', 'points' => 2.0, 'category' => 'Olimpiada Académica'],
           ['title' => 'Proyecto Voluntario / Modelado', 'type' => 'positive', 'points' => 1.2, 'category' => 'Proyectos'],
           ['title' => 'Asesoría Tutórica a Compañeros', 'type' => 'positive', 'points' => 1.0, 'category' => 'Apoyo Académico'],
           ['title' => 'Resolución de Desafío en Pizarrón', 'type' => 'positive', 'points' => 0.8, 'category' => 'Participación'],
           ['title' => 'Entrega Puntual y Bitácora Limpia', 'type' => 'positive', 'points' => 0.5, 'category' => 'Puntualidad y Orden'],
           ['title' => 'Entrega Tardía de Tarea', 'type' => 'negative', 'points' => 0.5, 'category' => 'Cumplimiento'],
           ['title' => 'Falta de Material en Laboratorio/Clase', 'type' => 'negative', 'points' => 0.5, 'category' => 'Responsabilidad'],
           ['title' => 'Conducta Inadecuada / Distracción', 'type' => 'negative', 'points' => 0.8, 'category' => 'Conducta'],
       ];

       foreach ($predefined as $p) {
           PredefinedIncidence::create($p);
       }

       // 1. Groups matching initialdata.js
       $groupWebDev = ClassGroup::create([
           'name' => 'SAB 8-12',
           'subject' => 'Programación de Bases de Datos',
           'grade_level' => 'DSR',
           'academic_year' => '30',
           'total_practices' => 10,
           'total_weeks' => 4,
           'current_week' => 4,
           'week_status' => 'materia_terminada',
       ]);

       ClassGroup::create([
           'name' => 'Grupo 3º B',
           'subject' => 'Matemáticas Avanzadas',
           'grade_level' => '3º Secundaria',
           'academic_year' => '2025 - 2026',
           'total_practices' => 12,
           'total_weeks' => 6,
           'current_week' => 3,
           'week_status' => 'normal',
       ]);

       ClassGroup::create([
           'name' => 'Grupo 2º B',
           'subject' => 'Ciencias y Física',
           'grade_level' => '2º Secundaria',
           'academic_year' => '2025 - 2026',
           'total_practices' => 10,
           'total_weeks' => 4,
           'current_week' => 2,
           'week_status' => 'normal',
       ]);

       ClassGroup::create([
           'name' => 'Grupo 1º C',
           'subject' => 'Historia Universal',
           'grade_level' => '1º Secundaria',
           'academic_year' => '2025 - 2026',
           'total_practices' => 8,
           'total_weeks' => 4,
           'current_week' => 1,
           'week_status' => 'normal',
       ]);

       // Add Practices for SAB 8-12
       for ($i = 1; $i <= 10; $i++) {
           Practice::create([
               'class_group_id' => $groupWebDev->id,
               'name' => "Práctica $i (Semana " . (int)ceil($i / 3) . ")",
               'weight' => 1.0,
           ]);
       }

       // 2. Initial Students matching initialdata.js
       $studentsData = [
           [
               'name' => 'Eli',
               'avatar' => 'https://ui-avatars.com/api/?name=Eli&length=1&background=4C1D95&color=E9D5FF',
               'gender' => 'F',
               'base_grade' => 10.0,
               'exam_grade' => 10.0,
               'evaluation_grades' => [
                   'ev-web-1' => 10.0,
                   'ev-web-2' => 10.0,
                   'ev-web-3' => 10.0,
                   'ev-web-4' => 10.0,
                   'ev-web-5' => 10.0,
                   'ev-web-6' => 10.0,
                   'ev-web-7' => 10.0,
                   'ev-web-8' => 10.0,
                   'ev-web-9' => 10.0,
                   'ev-web-10' => 10.0,
               ],
               'grade_histories' => [
                   [
                       'evaluation_name' => 'Práctica 1 (Semana 1)',
                       'old_score' => 9.5,
                       'new_score' => 10.0,
                       'date' => '2026-07-20 10:15:00',
                       'note' => 'Revisión de maquetación CSS completada con bonus.',
                   ],
                   [
                       'evaluation_name' => 'Práctica 2 (Semana 1)',
                       'old_score' => 9.0,
                       'new_score' => 10.0,
                       'date' => '2026-07-22 11:30:00',
                       'note' => 'Corrección de interactividad JS.',
                   ],
               ],
               'incidences' => [
                   [
                       'type' => 'positive',
                       'title' => 'Llegar a tiempo',
                       'points' => 5.0,
                       'category' => 'Puntualidad',
                       'date' => '2026-07-28',
                       'note' => 'Primer lugar estatal representando al colegio.',
                   ],
                   [
                       'type' => 'negative',
                       'title' => 'Uso de celular en clase',
                       'points' => 0.5,
                       'category' => 'Distracciones',
                       'date' => '2026-07-24',
                       'note' => 'Demostración práctica impecable.',
                   ],
               ],
           ],
           [
               'name' => 'Cris',
               'avatar' => 'https://ui-avatars.com/api/?name=Cris&length=1&background=1E3A8A&color=DBEAFE',
               'gender' => 'M',
               'base_grade' => 8.4,
               'exam_grade' => 8.0,
               'evaluation_grades' => [
                   'ev-web-1' => 10.0,
                   'ev-web-2' => 9.0,
                   'ev-web-3' => 9.5,
                   'ev-web-4' => 9.8,
                   'ev-web-5' => 9.7,
                   'ev-web-6' => 10.0,
               ],
               'grade_histories' => [
                   [
                       'evaluation_name' => 'Práctica 3 (Semana 1)',
                       'old_score' => 9.0,
                       'new_score' => 9.5,
                       'date' => '2026-07-21 09:40:00',
                       'note' => 'Optimización de componentes React.',
                   ],
               ],
               'incidences' => [
                   [
                       'type' => 'positive',
                       'title' => 'Asesoría Tutórica a Compañeros',
                       'points' => 1.0,
                       'category' => 'Apoyo Académico',
                       'date' => '2026-07-18',
                       'note' => 'Ayudó a 4 compañeros a regularizarse antes del parcial.',
                   ],
                   [
                       'type' => 'positive',
                       'title' => 'Resolución de Desafío en Pizarrón',
                       'points' => 1.0,
                       'category' => 'Participación',
                       'date' => '2026-07-25',
                       'note' => 'Resolvió el problema complejo en pizarrón.',
                   ],
                   [
                       'type' => 'positive',
                       'title' => 'Resolución de Desafío en Pizarrón',
                       'points' => 1.0,
                       'category' => 'Participación',
                       'date' => '2026-07-25',
                       'note' => 'Resolvió el problema complejo en pizarrón.',
                   ],
                   [
                       'type' => 'negative',
                       'title' => 'Resolución de Desafío en Pizarrón',
                       'points' => 3.5,
                       'category' => 'Participación',
                       'date' => '2026-07-25',
                       'note' => 'Resolvió el problema complejo en pizarrón.',
                   ],
               ],
           ],
           [
               'name' => 'Dany',
               'avatar' => 'https://ui-avatars.com/api/?name=Dany&length=1&background=14532D&color=DCFCE7',
               'gender' => 'F',
               'base_grade' => 8.0,
               'exam_grade' => 8.0,
               'evaluation_grades' => [
                   'ev-web-1' => 9.0,
                   'ev-web-2' => 9.0,
                   'ev-web-3' => 9.0,
                   'ev-web-4' => 9.0,
                   'ev-web-5' => 9.0,
                   'ev-web-6' => 9.0,
               ],
               'grade_histories' => [
                   [
                       'evaluation_name' => 'Práctica 1 (Semana 1)',
                       'old_score' => 8.5,
                       'new_score' => 9.5,
                       'date' => '2026-07-19 14:20:00',
                       'note' => 'Entrega extemporánea justificable con correcciones.',
                   ],
               ],
               'incidences' => [
                   [
                       'type' => 'positive',
                       'title' => 'Proyecto Voluntario / Modelado',
                       'points' => 4.5,
                       'category' => 'Proyectos',
                       'date' => '2026-07-21',
                       'note' => 'Presentación ejecutiva sobresaliente.',
                   ],
                   [
                       'type' => 'negative',
                       'title' => 'Proyecto Voluntario / Modelado',
                       'points' => 5.0,
                       'category' => 'Proyectos',
                       'date' => '2026-07-21',
                       'note' => 'Presentación ejecutiva sobresaliente.',
                   ],
               ],
           ],
           [
               'name' => 'Mich',
               'avatar' => 'https://ui-avatars.com/api/?name=Mich&length=1&background=78350F&color=FEF3C7',
               'gender' => 'F',
               'base_grade' => 8.0,
               'exam_grade' => 0.0,
               'evaluation_grades' => [
                   'ev-web-1' => 9.0,
                   'ev-web-2' => 9.0,
                   'ev-web-3' => 9.0,
                   'ev-web-4' => 9.0,
                   'ev-web-5' => 9.0,
                   'ev-web-6' => 9.0,
               ],
               'grade_histories' => [
                   [
                       'evaluation_name' => 'Práctica 1 (Semana 1)',
                       'old_score' => 8.5,
                       'new_score' => 9.5,
                       'date' => '2026-07-19 14:20:00',
                       'note' => 'Entrega extemporánea justificable con correcciones.',
                   ],
               ],
               'incidences' => [
                   [
                       'type' => 'positive',
                       'title' => 'Proyecto Voluntario / Modelado',
                       'points' => 0.0,
                       'category' => 'Proyectos',
                       'date' => '2026-07-21',
                       'note' => 'Presentación ejecutiva sobresaliente.',
                   ],
                   [
                       'type' => 'negative',
                       'title' => 'Proyecto Voluntario / Modelado',
                       'points' => 0.0,
                       'category' => 'Proyectos',
                       'date' => '2026-07-21',
                       'note' => 'Presentación ejecutiva sobresaliente.',
                   ],
               ],
           ],
       ];

       foreach ($studentsData as $data) {
           $student = Student::create([
               'class_group_id' => $groupWebDev->id,
               'name' => $data['name'],
               'avatar' => $data['avatar'],
               'gender' => $data['gender'],
               'base_grade' => $data['base_grade'],
               'exam_grade' => $data['exam_grade'],
               'evaluation_grades' => $data['evaluation_grades'],
           ]);

           foreach ($data['grade_histories'] as $gh) {
               GradeHistory::create([
                   'student_id' => $student->id,
                   'evaluation_name' => $gh['evaluation_name'],
                   'old_score' => $gh['old_score'],
                   'new_score' => $gh['new_score'],
                   'note' => $gh['note'],
                   'date' => $gh['date'],
               ]);
           }

           foreach ($data['incidences'] as $inc) {
               Incidence::create([
                   'student_id' => $student->id,
                   'type' => $inc['type'],
                   'title' => $inc['title'],
                   'points' => $inc['points'],
                   'category' => $inc['category'],
                   'note' => $inc['note'] ?? null,
                   'date' => $inc['date'],
               ]);
           }
       }
    }
}
