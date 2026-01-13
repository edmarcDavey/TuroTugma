<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strand extends Model
{
    protected $fillable = ['name', 'code', 'description'];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
