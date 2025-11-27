<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ScheduleEntry;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Section;

$subjectId = $argv[1] ?? null;
$period = $argv[2] ?? 2;
$sectionId = $argv[3] ?? null;

if(!$sectionId){ echo "Usage: php debug_teachers_for_slot_direct.php <subject_id|empty> <period> <section_id>\n"; exit(1); }

$subjectId = $subjectId === 'empty' ? null : (int)$subjectId;
$period = (int)$period; $sectionId = (int)$sectionId;

if($subjectId){ $subject = Subject::find($subjectId); $candidates = $subject ? $subject->teachers()->with(['gradeLevels','subjects'])->get() : collect(); }
else { $candidates = Teacher::with(['gradeLevels','subjects'])->get(); }

$run = \App\Models\SchedulingRun::first();
$assignedMap = ScheduleEntry::where('scheduling_run_id', $run->id)->where('period', $period)->get()->groupBy('teacher_id');

$gradeLevelId = Section::find($sectionId)->grade_level_id ?? null;

$out = [];
foreach($candidates as $t){
    $isAssigned = isset($assignedMap[$t->id]);
    $load = ScheduleEntry::where('scheduling_run_id', $run->id)->where('teacher_id', $t->id)->count();
    $available = true; // simplified
    $teaches = true; if($subjectId) $teaches = $t->subjects->contains('id', $subjectId);
    $gradeOk = true; if($gradeLevelId){ if(!($t->gradeLevels && $t->gradeLevels->count())) $gradeOk=false; elseif(!$t->gradeLevels->contains('id', $gradeLevelId)) $gradeOk=false; }
    if(!$available || !$teaches || !$gradeOk) continue;
    $out[] = ['id'=>$t->id,'name'=>$t->name,'load'=>$load,'grade_level_ids'=>$t->gradeLevels->pluck('id')->all()];
}

echo "Candidates count: " . count($out) . "\n";
foreach($out as $c) echo "{$c['id']} - {$c['name']} (load={$c['load']}) grade_ids=[".implode(',',$c['grade_level_ids'])."]\n";
