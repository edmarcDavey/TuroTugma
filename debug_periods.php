<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$jhConfig = \App\Models\SchedulingConfig::where('level', 'junior_high')->first();

echo "=== REGULAR SESSION ===\n";
$regularDayConfig = \App\Models\DayConfig::where('scheduling_config_id', $jhConfig->id)
    ->where('session_type', 'regular')
    ->where('is_active', true)
    ->first();

if ($regularDayConfig) {
    echo "Period Count: " . $regularDayConfig->period_count . "\n";
    echo "Duration: " . $regularDayConfig->period_duration . " min\n";
    echo "Start Time: " . $regularDayConfig->start_time . "\n";
    echo "Breaks: " . $regularDayConfig->breaks . "\n";
} else {
    echo "NO REGULAR CONFIG FOUND\n";
}

echo "\n=== SHORTENED SESSION ===\n";
$shortenedDayConfig = \App\Models\DayConfig::where('scheduling_config_id', $jhConfig->id)
    ->where('session_type', 'shortened')
    ->where('is_active', true)
    ->first();

if ($shortenedDayConfig) {
    echo "Period Count: " . $shortenedDayConfig->period_count . "\n";
    echo "Duration: " . $shortenedDayConfig->period_duration . " min\n";
    echo "Start Time: " . $shortenedDayConfig->start_time . "\n";
    $breaks = is_array($shortenedDayConfig->breaks) ? $shortenedDayConfig->breaks : json_decode($shortenedDayConfig->breaks, true);
    echo "Breaks: " . json_encode($breaks) . "\n";
} else {
    echo "NO SHORTENED CONFIG FOUND\n";
}

// Now test the period calculation
echo "\n=== CALCULATED PERIODS ===\n";

function calculatePeriods($dayConfig) {
    if (!$dayConfig) return [];
    
    $periods = [];
    $periodCount = $dayConfig->period_count ?? 8;
    $startTime = $dayConfig->start_time ?? '07:30';
    $duration = $dayConfig->period_duration ?? 50;
    
    $breaks = $dayConfig->breaks;
    if (is_string($breaks)) {
        $breaks = json_decode($breaks, true) ?? [];
    } elseif (!is_array($breaks)) {
        $breaks = [];
    }
    
    $currentTime = new \DateTime($startTime);
    for ($i = 1; $i <= $periodCount; $i++) {
        $endTime = clone $currentTime;
        $endTime->modify("+{$duration} minutes");
        
        $periods[] = [
            'number' => $i,
            'start' => $currentTime->format('H:i'),
            'end' => $endTime->format('H:i')
        ];
        
        $currentTime = $endTime;
        
        if (is_array($breaks)) {
            foreach (['morning', 'lunch', 'afternoon'] as $breakName) {
                if (isset($breaks[$breakName])) {
                    $breakConfig = $breaks[$breakName];
                    $afterPeriod = $breakConfig['after_period'] ?? null;
                    $breakDuration = $breakConfig['duration'] ?? 0;
                    $breakEnabled = $breakConfig['enabled'] ?? false;
                } else {
                    $afterPeriod = $breaks[$breakName . '_break_after_period'] ?? null;
                    $breakDuration = $breaks[$breakName . '_break_duration'] ?? 0;
                    $breakEnabled = $breaks[$breakName . '_break_enabled'] ?? false;
                }
                
                if ($breakEnabled && $afterPeriod == $i && $breakDuration > 0) {
                    $currentTime->modify("+{$breakDuration} minutes");
                }
            }
        }
    }
    
    return $periods;
}

$regularPeriods = calculatePeriods($regularDayConfig);
$shortenedPeriods = calculatePeriods($shortenedDayConfig);

echo "Regular Periods (" . count($regularPeriods) . "):\n";
foreach ($regularPeriods as $p) {
    echo "  P{$p['number']}: {$p['start']} - {$p['end']}\n";
}

echo "\nShortened Periods (" . count($shortenedPeriods) . "):\n";
foreach ($shortenedPeriods as $p) {
    echo "  P{$p['number']}: {$p['start']} - {$p['end']}\n";
}
