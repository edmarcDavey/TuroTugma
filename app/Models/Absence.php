<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id','date','period','reason','created_by','resolved'];

    protected $casts = [
        'date' => 'date',
        'resolved' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class);
    }

    public function substitutions()
    {
        return $this->hasMany(Substitution::class);
    }
}
