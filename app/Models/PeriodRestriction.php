<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodRestriction extends Model
{

        protected $fillable = [
            'scheduling_config_id',
            'day_of_week',
            'period_number',
            'restriction_type',
            'subject_id',
            'teacher_ancillary',
        ];

        public function config()
        {
            return $this->belongsTo(SchedulingConfig::class, 'scheduling_config_id');
        }

        public function subject()
        {
            return $this->belongsTo(Subject::class);
        }
    //
}
