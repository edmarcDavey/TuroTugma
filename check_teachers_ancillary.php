<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$teachers = \App\Models\User::where('role', 'teacher')->get();
echo "Total teachers: " . $teachers->count() . PHP_EOL;
echo PHP_EOL . "Teachers with special_assignment:" . PHP_EOL;

$withSpecial = $teachers->whereNotNull('special_assignment');
echo "Count: " . $withSpecial->count() . PHP_EOL;

if ($withSpecial->count() > 0) {
    echo PHP_EOL . "Special assignments found:" . PHP_EOL;
    $grouped = $withSpecial->groupBy('special_assignment');
    foreach ($grouped as $assignment => $group) {
        echo "  " . $assignment . ": " . $group->count() . " teachers" . PHP_EOL;
    }
    
    echo PHP_EOL . "Sample teachers with special assignments:" . PHP_EOL;
    foreach ($withSpecial->take(10) as $t) {
        echo "  " . $t->name . " - special_assignment: " . $t->special_assignment . PHP_EOL;
    }
}

echo PHP_EOL . "All unique special_assignment values in database:" . PHP_EOL;
$allSpecial = \App\Models\User::where('role', 'teacher')
    ->whereNotNull('special_assignment')
    ->distinct()
    ->pluck('special_assignment');
    
foreach ($allSpecial as $val) {
    echo "  - " . $val . PHP_EOL;
}
