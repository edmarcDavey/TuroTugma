<?php
// Update teacher grade level and subject assignments to ensure each teacher is assigned to a stage
// and has 1-2 grade levels and 2-5 subjects related to those grade levels or matching stage.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;
use App\Models\GradeLevel;
use App\Models\Subject;

echo "Starting teacher assignments update...\n";

$faker = \Faker\Factory::create();

$juniorLevels = GradeLevel::where('school_stage', 'junior')->get();
$seniorLevels = GradeLevel::where('school_stage', 'senior')->get();
$allSubjects = Subject::orderBy('name')->get();

if ($allSubjects->isEmpty()) {
    echo "No subjects found. Aborting.\n";
    exit(1);
}

$teachers = Teacher::all();
$total = $teachers->count();
if ($total === 0) {
    echo "No teachers found.\n";
    exit(0);
}

$i = 0;
$summary = ['updated' => 0, 'skipped' => 0];

foreach ($teachers as $t) {
    $i++;
    $oldGrades = $t->gradeLevels()->pluck('grade_levels.id')->toArray();
    $oldSubjects = $t->subjects()->pluck('subjects.id')->toArray();

    // choose stage randomly but aim for balanced distribution
    $stage = $faker->randomElement(['junior', 'senior']);
    $levelsPool = $stage === 'junior' ? $juniorLevels : $seniorLevels;
    if ($levelsPool->isEmpty()) {
        // fallback to whichever has entries
        $levelsPool = $juniorLevels->isNotEmpty() ? $juniorLevels : $seniorLevels;
    }

    // pick 1-2 grade levels
    $maxGrades = min(2, $levelsPool->count());
    $numGrades = $faker->numberBetween(1, max(1, $maxGrades));
    $pickedGrades = $levelsPool->random($numGrades);
    $gradeIds = $pickedGrades instanceof \Illuminate\Support\Collection ? $pickedGrades->pluck('id')->toArray() : [$pickedGrades->id];

    // build eligible subjects (prefer those attached to chosen grades)
    $subjectStageKey = $stage === 'junior' ? 'jhs' : 'shs';
    $eligible = collect();
    if (!empty($gradeIds)) {
        $eligible = Subject::whereHas('gradeLevels', function($q) use ($gradeIds) {
            $q->whereIn('grade_levels.id', $gradeIds);
        })->get();
    }
    if ($eligible->count() < 2) {
        $stageMatches = Subject::whereRaw('lower(stage) = ?', [strtolower($subjectStageKey)])->get();
        $eligible = $eligible->merge($stageMatches)->unique('id');
    }
    if ($eligible->count() < 2) {
        $eligible = $allSubjects;
    }
    $eligible = $eligible->unique('id')->values();

    $numSubjects = $faker->numberBetween(2, min(5, max(2, $eligible->count())));
    $pickedSubjects = $eligible->random($numSubjects);
    $subjectIds = collect($pickedSubjects)->pluck('id')->toArray();

    // sync (overwrite behavior)
    $t->gradeLevels()->sync($gradeIds);
    $t->subjects()->sync($subjectIds);

    echo "[{$i}/{$total}] {$t->name}: stage={$stage}, grades=" . implode(',', $gradeIds) . ", subjects=" . implode(',', $subjectIds) . "\n";
    $summary['updated']++;
}

echo "Done. Updated: {$summary['updated']} teachers.\n";

return 0;
