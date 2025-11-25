<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Substitution extends Model
{
    use HasFactory;

    protected $fillable = ['absence_id','substitute_teacher_id','schedule_entry_id','applied_at','created_by'];

    protected $dates = ['applied_at'];

    public function absence()
    {
        return $this->belongsTo(Absence::class);
    }

    public function substitute()
    {
        return $this->belongsTo(Teacher::class,'substitute_teacher_id');
    }

    public function scheduleEntry()
    {
        return $this->belongsTo(ScheduleEntry::class,'schedule_entry_id');
    }
}
