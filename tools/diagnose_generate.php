<?php
use Illuminate\Support\Facades\DB;
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Diagnostics for scheduling run=1" . PHP_EOL . PHP_EOL;

$runId = 1;

$unassignedTeachers = \App\Models\ScheduleEntry::where('scheduling_run_id', $runId)->whereNull('teacher_id')->count();
$unassignedSubjects = \App\Models\ScheduleEntry::where('scheduling_run_id', $runId)->whereNull('subject_id')->count();

echo "Unassigned entries (teacher_id IS NULL): $unassignedTeachers" . PHP_EOL;
echo "Unassigned subjects (subject_id IS NULL): $unassignedSubjects" . PHP_EOL . PHP_EOL;

// Top subjects where subject assigned but teacher is NULL
$rows = DB::table('schedule_entries')
    ->join('subjects','schedule_entries.subject_id','=','subjects.id')
    ->select('subjects.id','subjects.name', DB::raw('count(*) as cnt'))
    ->where('schedule_entries.scheduling_run_id',$runId)
    ->whereNotNull('schedule_entries.subject_id')
    ->whereNull('schedule_entries.teacher_id')
    ->groupBy('subjects.id','subjects.name')
    ->orderByDesc('cnt')
    ->get();

if(count($rows)==0){
    echo "No slots have a subject but lack a teacher.\n\n";
} else {
    echo "Top subjects that have subject assigned but no teacher (subject_id => count):\n";
    foreach($rows as $r){ echo "{$r->id} - {$r->name} => {$r->cnt}\n"; }
    echo "\n";
}

// subjects with zero teachers
$noTeacher = \App\Models\Subject::doesntHave('teachers')->get(['id','name']);
if(count($noTeacher)){
    echo "Subjects with no teachers assigned (total: " . count($noTeacher) . "):\n";
    foreach($noTeacher as $s) echo "{$s->id} - {$s->name}\n";
    echo "\n";
} else {
    echo "All subjects have at least one teacher assigned.\n\n";
}

// teacher counts per subject (top 20)
$tc = DB::table('teacher_subject')
    ->join('subjects','teacher_subject.subject_id','=','subjects.id')
    ->select('subjects.id','subjects.name', DB::raw('count(*) as c'))
    ->groupBy('subjects.id','subjects.name')
    ->orderByDesc('c')
    ->limit(20)
    ->get();

if(count($tc)){
    echo "Top subjects by number of teachers (subject_id - name => teacher_count):\n";
    foreach($tc as $r) echo "{$r->id} - {$r->name} => {$r->c}\n";
    echo "\n";
}

// Show teacher loads (top 20 highest assigned counts)
$loads = DB::table('schedule_entries')
    ->join('teachers','schedule_entries.teacher_id','=','teachers.id')
    ->select('teachers.id','teachers.name', DB::raw('count(*) as cnt'))
    ->where('schedule_entries.scheduling_run_id',$runId)
    ->whereNotNull('schedule_entries.teacher_id')
    ->groupBy('teachers.id','teachers.name')
    ->orderByDesc('cnt')
    ->limit(20)
    ->get();

if(count($loads)){
    echo "Top teacher loads in this run (teacher_id - name => assigned_count):\n";
    foreach($loads as $l) echo "{$l->id} - {$l->name} => {$l->cnt}\n";
    echo "\n";
} else {
    echo "No teacher load records (no teachers assigned).\n\n";
}

// sample of unassigned slots (first 20)
$unassignedSample = \App\Models\ScheduleEntry::with('section','subject')->where('scheduling_run_id',$runId)->whereNull('teacher_id')->limit(20)->get();
if(count($unassignedSample)){
    echo "Sample unassigned slots (first 20):\n";
    foreach($unassignedSample as $e){
        $sec = $e->section ? ($e->section->name ?? $e->section->id) : $e->section_id;
        $sub = $e->subject ? ($e->subject->name ?? $e->subject->id) : 'NULL';
        echo "Section: {$sec} | Day: {$e->day} | Period: {$e->period} | Subject: {$sub} | EntryID: {$e->id}\n";
    }
    echo "\n";
}

echo "Diagnostics complete." . PHP_EOL;
