<?php
// Simple runner to call the SchedulingRunController::generate for run 1
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Call the controller action; container will inject Request
app()->call('App\\Http\\Controllers\\Admin\\SchedulingRunController@generate', ['runId' => 1]);

echo "Generator finished" . PHP_EOL;
echo 'Entries: ' . \App\Models\ScheduleEntry::where('scheduling_run_id', 1)->count() . PHP_EOL;
echo 'Conflicts: ' . \App\Models\ScheduleEntry::where('scheduling_run_id', 1)->whereNotNull('conflict')->count() . PHP_EOL;
