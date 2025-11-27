<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ScheduleEntry;
use App\Models\Section;
use App\Models\Subject;

$sections = Section::with(['gradeLevel'])->get();
$report = [];
foreach($sections as $section){
    $isJhs = false;
    $stage = strtolower($section->gradeLevel->school_stage ?? '');
    if(strpos($stage,'jhs') !== false || strpos($stage,'jun') !== false) $isJhs = true;
    if(!$isJhs) continue;
    $isSectionSpecial = (bool)($section->is_special ?? false);
    $entries = ScheduleEntry::where('section_id',$section->id)->get();
    $specialCount = 0;
    $specialSubjects = [];
    foreach($entries as $e){
        if(!$e->subject_id) continue;
        $sub = Subject::find($e->subject_id);
        if(!$sub) continue;
        $t = strtolower(trim($sub->type ?? ''));
        $isSpecial = ($t === 'special subjects' || in_array(strtoupper($sub->code ?? ''), ['SPA','SPJ']));
        if($isSpecial){ $specialCount++; $specialSubjects[] = [$e->period, $sub->id, $sub->code ?? '', $sub->name]; }
    }
    if($specialCount > 1){
        $report[] = ['section_id'=>$section->id,'section_name'=>$section->name,'grade'=>$section->gradeLevel->year ?? null,'is_special'=>$isSectionSpecial,'special_count'=>$specialCount,'subjects'=>$specialSubjects];
    }
}

if(empty($report)){
    echo "No special-JHS sections with >1 special subject assigned.\n";
    exit(0);
}

foreach($report as $r){
    echo "Section {$r['section_id']} - {$r['section_name']} (Grade {$r['grade']}) special={$r['is_special']} count={$r['special_count']}\n";
    foreach($r['subjects'] as $s){
        echo "  P{$s[0]}: {$s[1]} ({$s[2]}) {$s[3]}\n";
    }
}

echo "Total sections with >1 special: " . count($report) . "\n";
