<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefinedIncidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'points',
        'category',
    ];

    protected $casts = [
        'points' => 'float',
    ];
}
