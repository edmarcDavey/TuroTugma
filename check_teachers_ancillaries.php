<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEACHERS WITH ANCILLARY ASSIGNMENTS ===" . PHP_EOL . PHP_EOL;

$teachers = \App\Models\Teacher::whereNotNull('ancillary_assignments')
    ->where('ancillary_assignments', '!=', '')
    ->orderBy('id')
    ->get();

echo "Total teachers with ancillary: " . $teachers->count() . PHP_EOL . PHP_EOL;

if ($teachers->count() > 0) {
    echo "Teacher ID | Name | Ancillary Assignment" . PHP_EOL;
    echo str_repeat("-", 100) . PHP_EOL;
    
    foreach ($teachers as $t) {
        echo sprintf("%-10d | %-30s | %s" . PHP_EOL,
            $t->id,
            substr($t->name, 0, 30),
            $t->ancillary_assignments
        );
    }
} else {
    echo "No teachers with ancillary assignments found!" . PHP_EOL;
}

echo PHP_EOL . "Sample of ALL 68 teachers:" . PHP_EOL;
echo str_repeat("-", 100) . PHP_EOL;
echo "ID | Name | Ancillary" . PHP_EOL;
echo str_repeat("-", 100) . PHP_EOL;

$allTeachers = \App\Models\Teacher::orderBy('id')->get();
foreach ($allTeachers->take(20) as $t) {
    echo sprintf("%-3d | %-30s | %s" . PHP_EOL,
        $t->id,
        substr($t->name, 0, 30),
        $t->ancillary_assignments ?? '(null)'
    );
}
