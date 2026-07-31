<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'evaluation_name',
        'old_score',
        'new_score',
        'note',
        'date',
    ];

    protected $casts = [
        'old_score' => 'float',
        'new_score' => 'float',
        'date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
