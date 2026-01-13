<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayConfig extends Model
{

        protected $fillable = [
            'scheduling_config_id',
            'day_of_week',
            'is_active',
            'session_type',
            'period_count',
            'start_time',
            'end_time',
            'period_duration',
            'breaks',
            'manual_period_times',
        ];

        protected $casts = [
            'is_active' => 'boolean',
            'breaks' => 'array',
            'manual_period_times' => 'array',
        ];

        public function config()
        {
            return $this->belongsTo(SchedulingConfig::class, 'scheduling_config_id');
        }
    //
}
