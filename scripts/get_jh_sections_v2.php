<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sections = \App\Models\Section::with('gradeLevel')
    ->orderBy('name')
    ->get();

// Filter for Junior High (grades 7-10)
$jhSections = $sections->filter(function($section) {
    return $section->gradeLevel && 
           $section->gradeLevel->level >= 7 && 
           $section->gradeLevel->level <= 10;
});

echo "Junior High Sections (Grade 7-10):\n";
echo "===================================\n";

foreach ($jhSections as $section) {
    echo "ID: {$section->id} | Name: {$section->name} | Grade: {$section->gradeLevel->level}\n";
}

echo "\nTotal JH Sections: " . $jhSections->count() . "\n";
echo "Total All Sections: " . $sections->count() . "\n";
