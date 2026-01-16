<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$config = \App\Models\SchedulingConfig::where('level', 'junior_high')->first();

// Build proper break config with enabled breaks
$breaks = [
    'morning' => [
        'enabled' => true,
        'duration' => 20,
        'after_period' => 3,
    ],
    'lunch' => [
        'enabled' => true,
        'duration' => 60,
        'after_period' => 6,
    ],
    'afternoon' => [
        'enabled' => false,
        'duration' => 15,
        'after_period' => 7,
    ],
];

// Update all active shortened day configs
$updated = \App\Models\DayConfig::where('scheduling_config_id', $config->id)
    ->where('session_type', 'shortened')
    ->where('is_active', true)
    ->update([
        'period_count' => 7,
        'period_duration' => 40,
        'breaks' => json_encode($breaks),
    ]);

echo "Updated $updated shortened day configs\n";
echo "New breaks config:\n";
echo json_encode($breaks, JSON_PRETTY_PRINT) . "\n";
