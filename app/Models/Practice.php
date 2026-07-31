<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'name',
        'weight',
    ];

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }
}
