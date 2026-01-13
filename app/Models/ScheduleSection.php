<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSection extends Model
{
    protected $fillable = [
        'level',
        'name',
        'student_count',
        'track',
        'grade_level',
        'required_subjects'
    ];

    protected $casts = [
        'required_subjects' => 'array'
    ];
}
