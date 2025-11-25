<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleEntry extends Model
{
    use HasFactory;

    protected $fillable = ['scheduling_run_id','section_id','day','period','subject_id','teacher_id','conflict'];

    protected $casts = [
        'conflict' => 'array',
    ];

    public function run()
    {
        return $this->belongsTo(SchedulingRun::class, 'scheduling_run_id');
    }

    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }
}
