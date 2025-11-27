<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ScheduleEntry;
use App\Models\Subject;
use App\Models\Section;

$entries = ScheduleEntry::with(['subject','section','section.gradeLevel'])->get();
$invalid = [];
foreach($entries as $e){
    if(!$e->subject) continue;
    $subtype = strtolower(trim($e->subject->type ?? ''));
    $isSpecialSubject = ($subtype === 'special subjects' || in_array(strtoupper($e->subject->code ?? ''), ['SPA','SPJ']));
    $section = $e->section;
    $isSectionSpecial = $section ? (bool)($section->is_special ?? false) : false;
    // only consider junior-high sections (grade stage contains 'jhs' or 'jun')
    $isJhs = false;
    if($section && $section->gradeLevel){
        $raw = strtolower($section->gradeLevel->school_stage ?? '');
        if(strpos($raw,'jhs') !== false || strpos($raw,'jun') !== false) $isJhs = true;
    }
    if($isJhs && $isSpecialSubject && !$isSectionSpecial){
        $invalid[] = [
            'entry_id' => $e->id,
            'section_id' => $section ? $section->id : null,
            'section_name' => $section ? $section->name : null,
            'grade' => $section && $section->gradeLevel ? $section->gradeLevel->year : null,
            'period' => $e->period,
            'subject_id' => $e->subject->id,
            'subject_name' => $e->subject->name,
            'subject_code' => $e->subject->code ?? null,
            'subject_type' => $e->subject->type ?? null,
        ];
    }
}

if(!count($invalid)){
    echo "No invalid special subject assignments found.\n";
    exit(0);
}

echo "Invalid assignments (special subject assigned to non-special JHS section):\n";
foreach($invalid as $i){
    echo 'Entry ' . $i['entry_id'] . ': Section ' . $i['section_id'] . ' - ' . ($i['section_name'] ?? '') . ' (Grade ' . ($i['grade'] ?? '') . ") Period P" . $i['period'] . ' => Subject ' . $i['subject_id'] . ' (' . ($i['subject_code'] ?? '') . ') ' . ($i['subject_name'] ?? '') . ' [type=' . ($i['subject_type'] ?? '') . ']'. PHP_EOL;
}

echo "Total: " . count($invalid) . "\n";
