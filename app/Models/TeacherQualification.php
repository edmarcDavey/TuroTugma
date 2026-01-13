<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherQualification extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'level',
        'proficiency',
        'seniority',
        'tracks'
    ];

    protected $casts = [
        'tracks' => 'array'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
