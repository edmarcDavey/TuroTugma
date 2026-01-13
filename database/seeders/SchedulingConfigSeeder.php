<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchedulingConfig;
use App\Models\DayConfig;

class SchedulingConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Junior High School Config
        $jhConfig = SchedulingConfig::updateOrCreate(
            ['level' => 'junior_high'],
            [
                'max_subjects_per_day' => 8,
                'max_periods_per_week' => 40,
                'weekly_load_limit' => null,
                'is_locked' => false,
                'unit_distribution_rules' => null,
            ]
        );

        // Default days for JHS: Monday-Friday, all regular sessions
        $daysOfWeek = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
        ];

        foreach ($daysOfWeek as $day => $label) {
            DayConfig::updateOrCreate(
                ['scheduling_config_id' => $jhConfig->id, 'day_of_week' => $day],
                [
                    'is_active' => true,
                    'session_type' => 'regular',
                    'period_count' => 8,
                    'start_time' => '07:30:00',
                    'end_time' => '16:30:00',
                    'period_duration' => 50,
                    'breaks' => [
                        'morning' => ['enabled' => true, 'duration' => 15, 'after_period' => 2],
                        'lunch' => ['enabled' => true, 'duration' => 60, 'after_period' => 4],
                        'afternoon' => ['enabled' => true, 'duration' => 15, 'after_period' => 6],
                    ],
                    'manual_period_times' => null,
                ]
            );
        }

        // Senior High School Config
        $shConfig = SchedulingConfig::updateOrCreate(
            ['level' => 'senior_high'],
            [
                'max_subjects_per_day' => 8,
                'max_periods_per_week' => 40,
                'weekly_load_limit' => null,
                'is_locked' => false,
                'unit_distribution_rules' => [
                    'spread_high_units' => true,
                    'avoid_back_to_back' => false,
                    'preferred_spacing' => 1,
                ],
            ]
        );

        // Default days for SHS: Monday-Friday, all regular sessions
        foreach ($daysOfWeek as $day => $label) {
            DayConfig::updateOrCreate(
                ['scheduling_config_id' => $shConfig->id, 'day_of_week' => $day],
                [
                    'is_active' => true,
                    'session_type' => 'regular',
                    'period_count' => 8,
                    'start_time' => '07:30:00',
                    'end_time' => '16:30:00',
                    'period_duration' => 50,
                    'breaks' => [
                        'morning' => ['enabled' => true, 'duration' => 15, 'after_period' => 2],
                        'lunch' => ['enabled' => true, 'duration' => 60, 'after_period' => 4],
                        'afternoon' => ['enabled' => true, 'duration' => 15, 'after_period' => 6],
                    ],
                    'manual_period_times' => null,
                ]
            );
        }
    }
}
