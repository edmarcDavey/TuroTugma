<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['grade_level_id','name','code','ordinal','advisor_user_id','capacity','school_year','is_active','notes'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_user_id');
    }
}
