<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\SchedulingConfig;
use App\Models\DayConfig;

echo "===== SCHEDULE GENERATION DEBUG =====\n\n";

// 1. Check if Junior High config exists
echo "1. SCHEDULING CONFIG CHECK:\n";
$config = SchedulingConfig::where('level', 'junior_high')->first();
if ($config) {
    echo "   ✓ JH Config found (ID: {$config->id})\n";
    $dayConfigs = DayConfig::where('scheduling_config_id', $config->id)->get();
    echo "   ✓ Day Configs: {$dayConfigs->count()}\n";
    foreach ($dayConfigs as $dc) {
        echo "     - Day {$dc->day_of_week} ({$dc->session_type}): active={$dc->is_active}, periods={$dc->period_count}\n";
    }
} else {
    echo "   ✗ No JH config found!\n";
}

echo "\n2. SECTIONS CHECK:\n";
$allSections = Section::count();
echo "   Total Sections: $allSections\n";

$jhSections = Section::whereHas('gradeLevel', function ($q) {
    $q->where('school_stage', 'junior');
})->with('gradeLevel')->get();

echo "   Junior High Sections: {$jhSections->count()}\n";
if ($jhSections->count() > 0) {
    foreach ($jhSections->take(3) as $s) {
        echo "     - {$s->name} (Grade Level: {$s->gradeLevel->name})\n";
    }
} else {
    echo "     ✗ NO JUNIOR HIGH SECTIONS FOUND!\n";
}

echo "\n3. SUBJECTS CHECK:\n";
$subjects = Subject::with('teachers')->get();
echo "   Total Subjects: {$subjects->count()}\n";
foreach ($subjects as $s) {
    $teacherCount = $s->teachers()->count();
    echo "     - {$s->code} ({$s->name}): $teacherCount teachers\n";
}

echo "\n4. TEACHERS CHECK:\n";
$teachers = Teacher::all();
echo "   Total Teachers: {$teachers->count()}\n";
if ($teachers->count() > 0) {
    foreach ($teachers->take(3) as $t) {
        $subjectCount = $t->subjects()->count();
        echo "     - {$t->name}: {$subjectCount} subjects\n";
    }
}

echo "\n5. SUBJECT-TEACHER RELATIONSHIPS:\n";
$subjectsWithTeachers = Subject::has('teachers')->count();
$subjectsWithoutTeachers = Subject::doesntHave('teachers')->count();
echo "   Subjects with teachers: $subjectsWithTeachers\n";
echo "   Subjects WITHOUT teachers: $subjectsWithoutTeachers\n";

if ($subjectsWithoutTeachers > 0) {
    echo "   These subjects have NO teachers:\n";
    $orphaned = Subject::doesntHave('teachers')->get();
    foreach ($orphaned as $s) {
        echo "     - {$s->code} ({$s->name})\n";
    }
}

echo "\n6. ACTIVE DAYS CHECK:\n";
if ($config) {
    $dayConfigs = DayConfig::where('scheduling_config_id', $config->id)
        ->where('is_active', true)
        ->get();
    echo "   Active Days: {$dayConfigs->count()}\n";
    foreach ($dayConfigs as $dc) {
        echo "     - Day {$dc->day_of_week} ({$dc->session_type})\n";
    }
}

echo "\n7. FILTERING LOGIC TEST:\n";
if ($jhSections->count() > 0 && $subjects->count() > 0) {
    $testSection = $jhSections->first();
    $testSubjects = $subjects->filter(function ($subject) use ($testSection) {
        // Filter out specialized subjects for regular sections
        if (!$testSection->is_special && in_array($subject->code, ['SPA', 'SPJ'])) {
            return false;
        }
        return true;
    });
    echo "   For section '{$testSection->name}' (is_special={$testSection->is_special}):\n";
    echo "   Available subjects: {$testSubjects->count()} out of {$subjects->count()}\n";
    
    // Check which have teachers
    $testSubjectsWithTeachers = $testSubjects->filter(function ($s) {
        return $s->teachers()->count() > 0;
    });
    echo "   Available subjects WITH teachers: {$testSubjectsWithTeachers->count()}\n";
}

echo "\n================================\n";
