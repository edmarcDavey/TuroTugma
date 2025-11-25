<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulingRun extends Model
{
    use HasFactory;

    protected $fillable = ['name','status','created_by','meta','locked_at','published_at'];

    protected $casts = [
        'meta' => 'array',
        'locked_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function entries()
    {
        return $this->hasMany(ScheduleEntry::class);
    }
}
