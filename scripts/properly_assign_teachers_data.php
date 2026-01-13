<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\GradeLevel;

echo "=== Properly Assigning Teacher Data ===\n\n";

// Get available subjects and grade levels
$subjects = Subject::orderBy('id')->get();
$gradeLevels = GradeLevel::whereIn('name', ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])->orderBy('id')->get();

echo "Available subjects ({$subjects->count()}):\n";
foreach ($subjects as $subject) {
    echo "  - {$subject->name}\n";
}
echo "\nAvailable grade levels ({$gradeLevels->count()}):\n";
foreach ($gradeLevels as $grade) {
    echo "  - {$grade->name}\n";
}

// Get all teachers
$teachers = Teacher::all();
echo "\nTotal teachers: {$teachers->count()}\n\n";

$gradeIds = $gradeLevels->pluck('id')->toArray();
$subjectIds = $subjects->pluck('id')->toArray();

// Possible grade level combinations (varied)
$gradeCombinations = [
    [$gradeIds[0]],                           // Grade 7 only
    [$gradeIds[1]],                           // Grade 8 only
    [$gradeIds[2]],                           // Grade 9 only
    [$gradeIds[3]],                           // Grade 10 only
    [$gradeIds[0], $gradeIds[1]],             // Grade 7, 8
    [$gradeIds[1], $gradeIds[2]],             // Grade 8, 9
    [$gradeIds[2], $gradeIds[3]],             // Grade 9, 10
    [$gradeIds[0], $gradeIds[1], $gradeIds[2]],  // Grade 7, 8, 9
    [$gradeIds[1], $gradeIds[2], $gradeIds[3]],  // Grade 8, 9, 10
    [$gradeIds[0], $gradeIds[2]],             // Grade 7, 9
    [$gradeIds[0], $gradeIds[3]],             // Grade 7, 10
    $gradeIds,                                 // All grades
];

echo "Starting assignment...\n\n";

$phoneCounter = 1;
$updated = 0;

foreach ($teachers as $index => $teacher) {
    // Assign unique phone number
    $uniquePhone = '+6390000' . str_pad($phoneCounter, 5, '0', STR_PAD_LEFT);
    $teacher->contact = $uniquePhone;
    $phoneCounter++;
    
    // Distribute subjects - cycle through subjects, some teachers get multiple
    $numSubjects = rand(1, 3); // Each teacher gets 1-3 subjects
    $teacherSubjects = [];
    
    // Start from a distributed position based on teacher index
    $startIndex = $index % count($subjectIds);
    for ($i = 0; $i < $numSubjects; $i++) {
        $subjectIndex = ($startIndex + $i) % count($subjectIds);
        $teacherSubjects[] = $subjectIds[$subjectIndex];
    }
    
    // Remove duplicates
    $teacherSubjects = array_unique($teacherSubjects);
    
    // Assign grade levels - use combinations
    $gradeComboIndex = $index % count($gradeCombinations);
    $teacherGrades = $gradeCombinations[$gradeComboIndex];
    
    // Save relationships
    $teacher->subjects()->sync($teacherSubjects);
    $teacher->gradeLevels()->sync($teacherGrades);
    $teacher->save();
    
    $updated++;
    
    $subjectNames = $subjects->whereIn('id', $teacherSubjects)->pluck('name')->toArray();
    $gradeNames = $gradeLevels->whereIn('id', $teacherGrades)->pluck('name')->toArray();
    
    echo "Updated {$teacher->name} ({$teacher->staff_id}):\n";
    echo "  Phone: {$uniquePhone}\n";
    echo "  Subjects: " . implode(', ', $subjectNames) . "\n";
    echo "  Grades: " . implode(', ', $gradeNames) . "\n\n";
}

echo "\n=== Summary ===\n";
echo "Teachers updated: {$updated}\n\n";

// Verify coverage
echo "=== Coverage Verification ===\n";
foreach ($subjects as $subject) {
    $count = $subject->teachers()->count();
    echo "{$subject->name}: {$count} teachers\n";
}

echo "\n";
foreach ($gradeLevels as $grade) {
    $count = $grade->teachers()->count();
    echo "{$grade->name}: {$count} teachers\n";
}

echo "\n=== Final Checks ===\n";
echo "Teachers missing contact: " . Teacher::whereNull('contact')->orWhere('contact', '')->count() . "\n";
echo "Teachers without subjects: " . Teacher::doesntHave('subjects')->count() . "\n";
echo "Teachers without grade levels: " . Teacher::doesntHave('gradeLevels')->count() . "\n";
echo "\nDone!\n";
