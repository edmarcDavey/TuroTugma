<?php
// Smoke test that calls SchedulingRunController::teachersForSlots directly
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Models\SchedulingRun;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Http\Controllers\Admin\SchedulingRunController;

$run = SchedulingRun::first();
if(!$run){ echo "No scheduling runs found.\n"; exit(1); }

$jhsGrade = GradeLevel::where('school_stage','like','%jhs%')->orWhere('school_stage','junior')->first();
$shsGrade = GradeLevel::where('school_stage','like','%shs%')->orWhere('school_stage','senior')->first();

function sampleForGrade($grade){
    $sections = $grade ? $grade->sections()->get() : collect();
    $section = $sections->first();
    $subjectList = [];
    if($section){
        $subjects = \App\Models\Subject::whereHas('gradeLevels', function($q) use ($grade){ $q->where('grade_levels.id', $grade->id); })->orderBy('name')->get();
        if($subjects->isEmpty()){
            $raw = strtolower($grade->school_stage ?? 'jhs');
            $stage = (str_contains($raw,'shs')||str_starts_with($raw,'sen')) ? 'shs':'jhs';
            $subjects = \App\Models\Subject::where('stage',$stage)->orWhereNull('stage')->orderBy('name')->get();
        }
        $subjectList = $subjects->pluck('id')->all();
    }
    return [$grade, $section, $subjectList];
}

list($g1,$s1,$subs1) = sampleForGrade($jhsGrade);
list($g2,$s2,$subs2) = sampleForGrade($shsGrade);

$controller = new SchedulingRunController();

echo "Run: {$run->id} - {$run->name}\n";

$slots = [];
if($s1 && !empty($subs1)){
    $slots[] = ['section_id' => $s1->id, 'period' => 2, 'subject_id' => $subs1[0], 'key' => 'jhs'];
}
if($s2 && !empty($subs2)){
    $slots[] = ['section_id' => $s2->id, 'period' => 2, 'subject_id' => $subs2[0], 'key' => 'shs'];
}

$req = Request::create('/','POST',[],[],[],[], json_encode(['slots'=>$slots]));
$req->headers->set('Content-Type','application/json');

$res = $controller->teachersForSlots($req, $run->id);

echo json_encode($res->getData(), JSON_PRETTY_PRINT) . "\n";

echo "Done.\n";
