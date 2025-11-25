<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Searching for subjects matching 'math'...\n\n";

$subs = \App\Models\Subject::where('name','like','%Math%')->orWhere('name','like','%Mathematics%')->get();
if(!$subs->count()){
    echo "No subjects found matching 'math' or 'Mathematics'.\n";
    exit;
}

foreach($subs as $s){
    echo "ID: {$s->id} | Name: {$s->name} | Stage: " . ($s->stage ?? '(null)') . "\n";
    $gl = $s->gradeLevels()->pluck('grade_levels.id','grade_levels.name')->toArray();
    if(count($gl)){
        echo "  Linked GradeLevels: \n";
        foreach($gl as $name => $id){ echo "    - {$name} (id={$id})\n"; }
    } else {
        echo "  Linked GradeLevels: none\n";
    }
    echo "  Teachers count: " . $s->teachers()->count() . "\n";
    echo "\n";
}

// Check if 'Mathematics' appears in subjectsByStage logic for jhs and shs
$jhs = \App\Models\Subject::where(function($q){ $q->where('stage','jhs')->orWhereNull('stage')->orWhere('stage',''); })->orWhereHas('gradeLevels', function($q){ $q->where('school_stage','jhs'); })->orderBy('name')->get(['id','name']);
$shs = \App\Models\Subject::where(function($q){ $q->where('stage','shs')->orWhereNull('stage')->orWhere('stage',''); })->orWhereHas('gradeLevels', function($q){ $q->where('school_stage','shs'); })->orderBy('name')->get(['id','name']);

echo "JHS subjects count: " . $jhs->count() . "\n";
$foundJ = $jhs->firstWhere('name','like','%Math%');
if($foundJ) echo "  JHS contains: {$foundJ->id} - {$foundJ->name}\n";

echo "SHS subjects count: " . $shs->count() . "\n";
$foundS = $shs->firstWhere('name','like','%Math%');
if($foundS) echo "  SHS contains: {$foundS->id} - {$foundS->name}\n";

// sample gradeLevels and their subjects for quick check
$gls = \App\Models\GradeLevel::with('sections')->orderBy('id')->limit(5)->get();
foreach($gls as $g){
    echo "GradeLevel {$g->id} ({$g->name} / stage={$g->school_stage}) subjects attached: ";
    $slist = \App\Models\Subject::whereHas('gradeLevels', function($q) use ($g){ $q->where('grade_levels.id', $g->id); })->pluck('name')->toArray();
    echo count($slist) . '\n';
}

echo "\nDone.\n";
