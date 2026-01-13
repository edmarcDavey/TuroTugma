<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\GradeLevel;

echo "=== Updating Teachers with Required Fields ===\n\n";

// Get available subjects and grade levels
$subjects = Subject::orderBy('id')->get();
$gradeLevels = GradeLevel::whereIn('name', ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])->orderBy('id')->get();

echo "Available subjects: " . $subjects->count() . "\n";
echo "Available grade levels: " . $gradeLevels->count() . "\n\n";

if ($subjects->isEmpty()) {
    echo "ERROR: No subjects found in database!\n";
    exit(1);
}

if ($gradeLevels->isEmpty()) {
    echo "ERROR: No grade levels found in database!\n";
    exit(1);
}

// Get all teachers
$teachers = Teacher::all();
echo "Total teachers to update: " . $teachers->count() . "\n\n";

$updated = 0;

foreach ($teachers as $teacher) {
    $changes = [];
    
    // Update phone if missing
    if (empty($teacher->contact)) {
        // Use a placeholder phone number in valid Philippine format
        $teacher->contact = '+639000000000';
        $changes[] = 'phone';
    }
    
    // Assign subjects if missing
    if ($teacher->subjects()->count() === 0) {
        // Assign first subject as default (could be randomized or based on designation)
        $teacher->subjects()->sync([$subjects->first()->id]);
        $changes[] = 'subject expertise';
    }
    
    // Assign grade levels if missing
    if ($teacher->gradeLevels()->count() === 0) {
        // Assign all junior high grade levels (7-10)
        $teacher->gradeLevels()->sync($gradeLevels->pluck('id')->toArray());
        $changes[] = 'grade level assignment';
    }
    
    if (!empty($changes)) {
        $teacher->save();
        $updated++;
        echo "Updated {$teacher->name} ({$teacher->staff_id}): " . implode(', ', $changes) . "\n";
    }
}

echo "\n=== Summary ===\n";
echo "Teachers updated: {$updated}\n";
echo "\n=== Verification ===\n";
echo "Teachers missing contact: " . Teacher::whereNull('contact')->orWhere('contact', '')->count() . "\n";
echo "Teachers without subjects: " . Teacher::doesntHave('subjects')->count() . "\n";
echo "Teachers without grade levels: " . Teacher::doesntHave('gradeLevels')->count() . "\n";
echo "\nDone!\n";
