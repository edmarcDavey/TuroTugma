<?php
require __DIR__ . '/../vendor/autoload.php';

// Boot Laravel app
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SchedulingRun;
use App\Models\ScheduleEntry;
use App\Models\Section;
use App\Models\Subject;

$run = SchedulingRun::first();
if (!$run) { echo "No SchedulingRun found\n"; exit(1); }

$sections = Section::orderBy('id')->get();

foreach ($sections as $section) {
    $entries = ScheduleEntry::where('scheduling_run_id', $run->id)
        ->where('section_id', $section->id)
        ->orderBy('day')
        ->orderBy('period')
        ->get();
    $total = $entries->count();
    $subjectCounts = [];
    foreach ($entries as $e) {
        $sid = $e->subject_id ?? 'NULL';
        if (!isset($subjectCounts[$sid])) $subjectCounts[$sid] = 0;
        $subjectCounts[$sid]++;
    }
    // count distinct non-null subjects
    $distinctSubjects = count(array_filter(array_keys($subjectCounts), fn($k) => $k !== 'NULL'));
    echo "Section: {$section->name} (ID {$section->id}) - total slots={$total}, distinct_subjects={$distinctSubjects}\n";
    foreach ($subjectCounts as $sid => $cnt) {
        $name = $sid === 'NULL' ? 'NULL' : (Subject::find($sid)?->name ?? "#{$sid}");
        echo "  - {$name} => {$cnt}\n";
    }
}

echo "Done.\n";
