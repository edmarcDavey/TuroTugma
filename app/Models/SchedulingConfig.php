<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulingConfig extends Model
{

        protected $fillable = [
            'level',
            'max_subjects_per_day',
            'max_periods_per_week',
            'weekly_load_limit',
            'is_locked',
            'unit_distribution_rules',
            'max_consecutive_periods',
            'max_teaching_days_per_week',
            'load_distribution_threshold',
            'senior_junior_ratio',
            'jhs_constraints',
            'shs_constraints',
              'faculty_restrictions',
              'optimization_settings'
        ];

        protected $casts = [
            'is_locked' => 'boolean',
            'unit_distribution_rules' => 'array',
            'jhs_constraints' => 'array',
            'shs_constraints' => 'array',
              'faculty_restrictions' => 'array',
              'optimization_settings' => 'array'
        ];

        public function dayConfigs()
        {
            return $this->hasMany(DayConfig::class);
        }

        public function periodRestrictions()
        {
            return $this->hasMany(PeriodRestriction::class);
        }

        public static function getForLevel(string $level)
        {
            return self::with(['dayConfigs', 'periodRestrictions.subject'])
                ->where('level', $level)
                ->first();
        }
    //
}
