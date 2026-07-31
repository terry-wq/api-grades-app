<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'grade_level',
        'academic_year',
        'total_practices',
        'total_weeks',
        'current_week',
        'week_status',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'total_practices' => 'integer',
        'total_weeks' => 'integer',
        'current_week' => 'integer',
    ];

    public function students()
    {
        return $this->hasMany(Student::class)->where('is_deleted', false);
    }

    public function practices()
    {
        return $this->hasMany(Practice::class);
    }
}
