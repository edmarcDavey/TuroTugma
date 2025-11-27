<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\GradeLevel;

$spa = Subject::where('code','SPA')->orWhere('name','like','%Special Program in the Arts%')->first();
$spj = Subject::where('code','SPJ')->orWhere('name','like','%Special Program in Journalism%')->first();
if (!$spa || !$spj) { echo "SPA or SPJ subject not found\n"; exit(1); }

// JHS grade level ids (years 7-10)
$jhsGradeIds = GradeLevel::whereBetween('year',[7,10])->pluck('id')->all();
if (!count($jhsGradeIds)) { echo "No JHS grade levels found\n"; exit(1); }

echo "JHS grade ids: " . implode(',', $jhsGradeIds) . "\n\n";

// Function to show teacher details
$showTeacher = function(Teacher $t){
    $g = $t->gradeLevels->map(fn($x)=>($x->id . ':' . ($x->name ?? $x->year)))->values()->all();
    $s = $t->subjects->map(fn($x)=>($x->id . ':' . $x->name))->values()->all();
    echo "Teacher {$t->id} - {$t->name}\n";
    echo "  grade_levels: " . (count($g)?implode(', ',$g):'(none)') . "\n";
    echo "  subjects: " . (count($s)?implode(' | ',$s):'(none)') . "\n";
};

// Process SPA teachers
echo "=== SPA ({$spa->id}) - {$spa->name} teachers BEFORE ===\n";
foreach ($spa->teachers as $t) {
    $t->load('gradeLevels','subjects');
    $showTeacher($t);
}

// Attach missing JHS grade levels to SPA teachers
foreach ($spa->teachers as $t) {
    $existing = $t->gradeLevels->pluck('id')->all();
    $toAttach = array_values(array_diff($jhsGradeIds, $existing));
    if (count($toAttach)) {
        $t->gradeLevels()->syncWithoutDetaching($toAttach);
        echo "Attached teacher {$t->id} to grade levels: " . implode(',', $toAttach) . "\n";
    }
}

// Process SPJ teachers
echo "\n=== SPJ ({$spj->id}) - {$spj->name} teachers BEFORE ===\n";
foreach ($spj->teachers as $t) {
    $t->load('gradeLevels','subjects');
    $showTeacher($t);
}

foreach ($spj->teachers as $t) {
    $existing = $t->gradeLevels->pluck('id')->all();
    $toAttach = array_values(array_diff($jhsGradeIds, $existing));
    if (count($toAttach)) {
        $t->gradeLevels()->syncWithoutDetaching($toAttach);
        echo "Attached teacher {$t->id} to grade levels: " . implode(',', $toAttach) . "\n";
    }
}

// Show after
echo "\n=== SPA teachers AFTER ===\n";
foreach ($spa->teachers as $t) { $t->load('gradeLevels','subjects'); $showTeacher($t); }

echo "\n=== SPJ teachers AFTER ===\n";
foreach ($spj->teachers as $t) { $t->load('gradeLevels','subjects'); $showTeacher($t); }

echo "Done.\n";
