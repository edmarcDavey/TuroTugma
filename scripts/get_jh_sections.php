<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sections = \App\Models\Section::where('grade_level', '>=', 7)
    ->where('grade_level', '<=', 10)
    ->orderBy('grade_level')
    ->orderBy('name')
    ->get(['id', 'name', 'grade_level']);

echo "Junior High Sections (Grade 7-10):\n";
echo "===================================\n";

foreach ($sections as $section) {
    echo $section->name . " (Grade " . $section->grade_level . ")\n";
}

echo "\nTotal: " . $sections->count() . " sections\n";
