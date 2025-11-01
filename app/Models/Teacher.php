<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'name',
        'email',
        'contact',
        'max_load_per_week',
        'max_load_per_day',
        'availability',
        'preferences',
        'notes',
    ];

    protected $casts = [
        'availability' => 'array',
        'preferences' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    public function gradeLevels()
    {
        return $this->belongsToMany(GradeLevel::class, 'teacher_gradelevel');
    }
}
