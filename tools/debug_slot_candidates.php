<?php
// Debug candidate reasons for a subject/section/period using SchedulingRunController
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Models\SchedulingRun;
use App\Models\ScheduleEntry;
use App\Models\Subject;
use App\Http\Controllers\Admin\SchedulingRunController;

$subjectId = $argv[1] ?? null;
$sectionId = $argv[2] ?? null;
$period = $argv[3] ?? null;

$run = SchedulingRun::first();
if(!$run){ echo "No scheduling run found.\n"; exit(1); }

if(!$subjectId){
    // default to English subject if exists
    $sub = Subject::where('name','like','%English%')->first();
    if(!$sub){ echo "No subject id provided and no 'English' subject found.\n"; exit(1); }
    $subjectId = $sub->id;
}

if(!$sectionId || !$period){
    // try to find an existing entry with this subject
    $entry = ScheduleEntry::where('scheduling_run_id',$run->id)->where('subject_id',$subjectId)->first();
    if($entry){
        $sectionId = $sectionId ?: $entry->section_id;
        $period = $period ?: $entry->period;
    } else {
        // fallback: pick any section from a grade that has the subject
        $subject = Subject::find($subjectId);
        $section = null;
        if($subject){
            $grades = $subject->gradeLevels()->pluck('grade_levels.id')->all();
            if($grades){
                $section = \App\Models\Section::whereIn('grade_level_id',$grades)->first();
            }
        }
        if($section){ $sectionId = $sectionId ?: $section->id; $period = $period ?: 2; }
    }
}

if(!$sectionId || !$period){ echo "Failed to find section/period for subject {$subjectId}. Provide section_id and period as arguments.\n"; exit(1); }

echo "Debugging candidates for Subject {$subjectId}, Section {$sectionId}, Period {$period}\n";

$controller = new SchedulingRunController();
$req = Request::create('/','POST',[],[],[],[], json_encode(['slots'=>[['section_id'=>intval($sectionId),'period'=>intval($period),'subject_id'=>intval($subjectId),'key'=>'debug']]]));
$req->headers->set('Content-Type','application/json');
$res = $controller->teachersForSlots($req, $run->id);
$data = $res->getData(true);
$results = $data['results'] ?? [];
$items = $results['debug'] ?? [];

foreach($items as $i){
    // derive flags if possible
    $id = $i['id'];
    $name = $i['name'];
    $is_assigned = $i['is_assigned'] ? 'true' : 'false';
    $eligible = $i['eligible'] ? 'true' : 'false';
    $notes = $i['notes'] ?? '';
    $grade_ids = implode(',', $i['grade_level_ids'] ?? []);
    $subjects = implode(', ', array_map(fn($s)=>$s['id'].'('.$s['name'].')', $i['subjects']));
    echo "- {$id}: {$name}\n  subjects: {$subjects}\n  grade_level_ids: {$grade_ids}\n  is_assigned: {$is_assigned}\n  eligible: {$eligible}\n  notes: {$notes}\n\n";
}

if(empty($items)) echo "No candidates returned for that slot.\n";
