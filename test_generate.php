<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test basic database connection
try {
    \DB::connection()->getPdo();
    echo "Database connection: OK\n";
} catch (Exception $e) {
    echo "Database connection FAILED: " . $e->getMessage() . "\n";
}

// Test if models exist
try {
    $count = \App\Models\SchedulingConfig::count();
    echo "SchedulingConfig model: OK ($count records)\n";
} catch (Exception $e) {
    echo "SchedulingConfig model FAILED: " . $e->getMessage() . "\n";
}

try {
    $count = \App\Models\Section::count();
    echo "Section model: OK ($count records)\n";
} catch (Exception $e) {
    echo "Section model FAILED: " . $e->getMessage() . "\n";
}

// Test route exists
try {
    $route = \Route::getRoutes()->getByAction('App\Http\Controllers\Admin\SchedulingConfigController@generateSchedule');
    echo "Route: " . ($route ? "OK" : "NOT FOUND") . "\n";
} catch (Exception $e) {
    echo "Route test FAILED: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
