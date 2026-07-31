<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'name',
        'avatar',
        'gender',
        'base_grade',
        'exam_grade',
        'evaluation_grades',
        'is_deleted',
    ];

    protected $casts = [
        'base_grade' => 'float',
        'exam_grade' => 'float',
        'evaluation_grades' => 'array',
        'is_deleted' => 'boolean',
    ];

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }

    public function gradeHistories()
    {
        return $this->hasMany(GradeHistory::class);
    }

    // Dynamic Calculations:
    // 1. Average of practices
    // 2. Add exam grade and divide by 2 => computed base grade
    // 3. Apply net incidences => final score
    public function getComputedBaseGradeAttribute()
    {
        $evals = $this->evaluation_grades;
        $hasPractices = is_array($evals) && count($evals) > 0;
        
        if ($hasPractices) {
            $vals = array_values($evals);
            $averagePractices = count($vals) > 0 ? (array_sum($vals) / count($vals)) : 0;
            $examGrade = (float) ($this->exam_grade ?? 0);
            return round(($averagePractices + $examGrade) / 2, 1);
        }

        $examGrade = (float) ($this->exam_grade ?? 0);
        if ($examGrade > 0) {
            return round($examGrade, 1);
        }

        return (float) $this->base_grade;
    }

    public function getTotalPositivePointsAttribute()
    {
        return round((float) $this->incidences()->where('type', 'positive')->sum('points'), 1);
    }

    public function getTotalNegativePointsAttribute()
    {
        return round((float) $this->incidences()->where('type', 'negative')->sum('points'), 1);
    }

    public function getNetIncidencePointsAttribute()
    {
        return round($this->total_positive_points - $this->total_negative_points, 1);
    }

    public function getRawScoreAttribute()
    {
        return round($this->computed_base_grade + $this->net_incidence_points, 1);
    }

    public function getFinalScoreAttribute()
    {
        $raw = max(0, $this->raw_score);
        return $raw > 10.0 ? 10.0 : $raw;
    }

    public function getExceedsTenAttribute()
    {
        return $this->raw_score > 10.0;
    }

    public function getExtraPointsAttribute()
    {
        return $this->raw_score > 10.0 ? round($this->raw_score - 10.0, 2) : 0.0;
    }
}
