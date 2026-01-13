<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check grade levels
$gradeLevels = \App\Models\GradeLevel::all();
echo "Grade Levels:\n";
echo "=============\n";
foreach ($gradeLevels as $gl) {
    echo "ID: {$gl->id} | Level: {$gl->level} | Name: {$gl->name}\n";
}

echo "\n\nSections with Grade Levels:\n";
echo "===========================\n";
$sections = \App\Models\Section::with('gradeLevel')->get();
foreach ($sections as $section) {
    $glLevel = $section->gradeLevel ? $section->gradeLevel->level : 'NULL';
    $glName = $section->gradeLevel ? $section->gradeLevel->name : 'NULL';
    echo "Section: {$section->name} | Grade Level ID: {$section->grade_level_id} | Level: {$glLevel} | GL Name: {$glName}\n";
}

echo "\n\nJunior High Filter Test:\n";
echo "========================\n";
$jhSections = \App\Models\Section::with('gradeLevel')
    ->whereHas('gradeLevel', function($query) {
        $query->whereBetween('level', [7, 10]);
    })
    ->orderBy('name')
    ->get();
echo "Found " . $jhSections->count() . " Junior High sections\n";
