<?php
// Smoke test for teachersForSlot logic for representative slots
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SchedulingRun;
use App\Models\ScheduleEntry;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;

function getCandidatesSnapshot($runId, $subjectId, $period, $sectionId){
    $run = SchedulingRun::findOrFail($runId);
    $subjectId = $subjectId ?: null;
    $period = $period ?: null;
    $sectionId = $sectionId ?: null;

    if ($subjectId) {
        $subject = Subject::find($subjectId);
        $candidates = $subject ? $subject->teachers()->with(['gradeLevels','subjects'])->get() : collect();
    } else {
        $candidates = Teacher::with(['gradeLevels','subjects'])->get();
    }

    $assignedMap = ScheduleEntry::where('scheduling_run_id', $run->id)
        ->where('period', $period)
        ->get()
        ->groupBy('teacher_id');

    $loads = ScheduleEntry::where('scheduling_run_id', $run->id)
        ->whereNotNull('teacher_id')
        ->groupBy('teacher_id')
        ->selectRaw('teacher_id, count(*) as cnt')
        ->pluck('cnt','teacher_id')
        ->all();

    $gradeLevelId = null;
    if ($sectionId) {
        $section = Section::with('gradeLevel')->find($sectionId);
        $gradeLevelId = $section ? $section->grade_level_id : null;
    }

    $out = [];
    foreach ($candidates as $t) {
        $isAssigned = isset($assignedMap[$t->id]);
        $load = (int) ($loads[$t->id] ?? 0);

        $available = true;
        if (is_array($t->availability) && count($t->availability)) {
            $flat = [];
            if (isset($t->availability['unavailable']) && is_array($t->availability['unavailable'])) {
                $flat = $t->availability['unavailable'];
            } else {
                $flat = $t->availability;
            }
            foreach ($flat as $f) {
                if (is_string($f) && (str_ends_with($f, ":{$period}") || (string)$f === (string)$period)) { $available = false; break; }
            }
        }

        $teaches = true;
        if ($subjectId) {
            $teaches = $t->subjects->contains('id', $subjectId);
        }

        $gradeOk = true;
        if ($gradeLevelId) {
            if (!($t->gradeLevels && $t->gradeLevels->count())) $gradeOk = false;
            elseif (!$t->gradeLevels->contains('id', $gradeLevelId)) $gradeOk = false;
        }

        $eligible = $available && !$isAssigned && $teaches && $gradeOk;
        $notes = null;
        if (!$available) $notes = 'Unavailable';
        elseif ($isAssigned) $notes = 'Already assigned at this period';
        elseif (!$teaches) $notes = 'Does not teach selected subject';
        elseif (!$gradeOk) $notes = 'Not assigned to this grade level';

        $out[] = [
            'id' => $t->id,
            'name' => $t->name,
            'load' => $load,
            'is_assigned' => (bool) $isAssigned,
            'eligible' => (bool) $eligible,
            'grade_level_ids' => $t->gradeLevels->pluck('id')->all(),
            'subjects' => $t->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->all(),
            'notes' => $notes,
        ];
    }

    usort($out, function($a,$b){
        if ($a['eligible'] !== $b['eligible']) return $a['eligible'] ? -1 : 1;
        if ($a['load'] !== $b['load']) return $a['load'] <=> $b['load'];
        return strcmp($a['name'], $b['name']);
    });

    return $out;
}

// pick a run
$run = SchedulingRun::first();
if(!$run){ echo "No scheduling runs found.\n"; exit(1); }

// find one JHS and one SHS grade and a section in each
$jhsGrade = GradeLevel::where('school_stage','like','%jhs%')->orWhere('school_stage','junior')->first();
$shsGrade = GradeLevel::where('school_stage','like','%shs%')->orWhere('school_stage','senior')->first();

function sampleForGrade($grade){
    $sections = $grade ? $grade->sections()->get() : collect();
    $section = $sections->first();
    $subjectList = [];
    if($section){
        // prefer grade-attached
        $subjects = Subject::whereHas('gradeLevels', function($q) use ($grade){ $q->where('grade_levels.id', $grade->id); })->orderBy('name')->get();
        if($subjects->isEmpty()){
            $raw = strtolower($grade->school_stage ?? 'jhs');
            $stage = (str_contains($raw,'shs')||str_starts_with($raw,'sen')) ? 'shs':'jhs';
            $subjects = Subject::where('stage',$stage)->orWhereNull('stage')->orderBy('name')->get();
        }
        $subjectList = $subjects->pluck('id')->all();
    }
    return [$grade, $section, $subjectList];
}

list($g1,$s1,$subs1) = sampleForGrade($jhsGrade);
list($g2,$s2,$subs2) = sampleForGrade($shsGrade);

echo "Run: {$run->id} - {$run->name}\n";

if($g1){
    echo "\nJHS grade: {$g1->id} ({$g1->year}) section: " . ($s1? $s1->id : 'none') . "\n";
    echo "Subjects (sample): "; print_r(array_slice($subs1,0,8));
    $sub = $subs1[0] ?? null;
    $cands = getCandidatesSnapshot($run->id, $sub, 2, $s1? $s1->id : null);
    echo "Candidates for subject {$sub} (period 2):\n";
    echo json_encode($cands, JSON_PRETTY_PRINT) . "\n";
} else { echo "No JHS grade found.\n"; }

if($g2){
    echo "\nSHS grade: {$g2->id} ({$g2->year}) section: " . ($s2? $s2->id : 'none') . "\n";
    echo "Subjects (sample): "; print_r(array_slice($subs2,0,8));
    $sub = $subs2[0] ?? null;
    $cands = getCandidatesSnapshot($run->id, $sub, 2, $s2? $s2->id : null);
    echo "Candidates for subject {$sub} (period 2):\n";
    echo json_encode($cands, JSON_PRETTY_PRINT) . "\n";
} else { echo "No SHS grade found.\n"; }

echo "\nDone.\n";
