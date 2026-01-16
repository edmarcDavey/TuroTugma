<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$config = \App\Models\SchedulingConfig::where('level', 'junior_high')->first();
$breaks = json_encode([
    'morning' => ['enabled' => true, 'duration' => 20, 'after_period' => 3],
    'lunch' => ['enabled' => true, 'duration' => 60, 'after_period' => 5],
    'afternoon' => ['enabled' => false, 'duration' => 15, 'after_period' => 7]
]);

// Save for Monday (0) - typical shortened day
\App\Models\DayConfig::updateOrCreate(
    ['scheduling_config_id' => $config->id, 'day_of_week' => 0, 'session_type' => 'shortened'],
    ['is_active' => true, 'period_count' => 8, 'start_time' => '07:30', 'period_duration' => 40, 'breaks' => $breaks]
);

echo "Shortened session config saved\n";
