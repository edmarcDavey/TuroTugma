<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['code','name','type','category','track','stage','units','strand_id','hours_per_week'];

    public function strand()
    {
        return $this->belongsTo(Strand::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject');
    }

    public function gradeLevels()
    {
        return $this->belongsToMany(GradeLevel::class, 'subject_gradelevel');
    }
}
