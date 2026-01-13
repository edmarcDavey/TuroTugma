<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sections = \App\Models\Section::orderBy('grade_level')->orderBy('name')->get();

echo "All Sections in Database:\n";
echo "=========================\n";

foreach ($sections as $section) {
    echo "ID: {$section->id} | Name: {$section->name} | Grade: {$section->grade_level}\n";
}

echo "\nTotal: " . $sections->count() . " sections\n";
