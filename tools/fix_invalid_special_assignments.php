<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ScheduleEntry;
use App\Models\Subject;
use App\Models\Section;

$entries = ScheduleEntry::with(['subject','section','section.gradeLevel'])->get();
$fixed = [];
foreach($entries as $e){
    if(!$e->subject) continue;
    $subtype = strtolower(trim($e->subject->type ?? ''));
    $isSpecialSubject = ($subtype === 'special subjects' || in_array(strtoupper($e->subject->code ?? ''), ['SPA','SPJ']));
    $section = $e->section;
    $isSectionSpecial = $section ? (bool)($section->is_special ?? false) : false;
    $isJhs = false;
    if($section && $section->gradeLevel){
        $raw = strtolower($section->gradeLevel->school_stage ?? '');
        if(strpos($raw,'jhs') !== false || strpos($raw,'jun') !== false) $isJhs = true;
    }
    if($isJhs && $isSpecialSubject && !$isSectionSpecial){
        // Clear the subject and teacher so the slot becomes unassigned
        $e->subject_id = null;
        $e->teacher_id = null;
        $e->save();
        $fixed[] = [
            'entry_id' => $e->id,
            'section_id' => $section ? $section->id : null,
            'section_name' => $section ? $section->name : null,
            'period' => $e->period,
            'removed_subject_id' => $e->subject->id,
            'removed_subject_name' => $e->subject->name,
        ];
    }
}

if(!count($fixed)){
    echo "No invalid special subject assignments to fix.\n";
    exit(0);
}

echo "Fixed entries:\n";
foreach($fixed as $f){
    echo 'Entry ' . $f['entry_id'] . ': Section ' . $f['section_id'] . ' - ' . ($f['section_name'] ?? '') . ' Period P' . $f['period'] . ' -> removed subject ' . $f['removed_subject_id'] . ' (' . ($f['removed_subject_name'] ?? '') . ')' . PHP_EOL;
}

echo "Total fixed: " . count($fixed) . "\n";
