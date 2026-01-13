<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USER DATABASE ANALYSIS ===" . PHP_EOL . PHP_EOL;

$users = \App\Models\User::all();
echo "Total users in database: " . $users->count() . PHP_EOL . PHP_EOL;

echo "Users by role:" . PHP_EOL;
$grouped = $users->groupBy('role');
foreach ($grouped as $role => $group) {
    $roleName = $role ?: '(null/empty)';
    echo "  " . $roleName . ": " . $group->count() . " users" . PHP_EOL;
}

echo PHP_EOL . "Checking 'special_assignment' column:" . PHP_EOL;
$withSpecial = $users->whereNotNull('special_assignment')->where('special_assignment', '!=', '');
echo "Users with special_assignment: " . $withSpecial->count() . PHP_EOL;

if ($withSpecial->count() > 0) {
    foreach ($withSpecial->groupBy('special_assignment') as $assignment => $group) {
        echo "  " . $assignment . ": " . $group->count() . PHP_EOL;
    }
}

echo PHP_EOL . "Sample of first 10 users:" . PHP_EOL;
foreach ($users->take(10) as $u) {
    echo sprintf("  ID: %d, Name: %s, Email: %s, Role: %s, Special: %s" . PHP_EOL,
        $u->id,
        $u->name,
        $u->email,
        $u->role ?? '(null)',
        $u->special_assignment ?? '(null)'
    );
}
