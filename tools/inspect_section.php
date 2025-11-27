<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Section;
use App\Models\ScheduleEntry;
use App\Models\Subject;

$q = $argv[1] ?? null;
if(!$q){ echo "Usage: php inspect_section.php <section name substring>\n"; exit(1); }

$sections = Section::where('name','like','%'.$q.'%')->with('gradeLevel')->get();
if(!$sections->count()){ echo "No sections matching '$q'\n"; exit(0); }
foreach($sections as $s){
    echo "Section {$s->id} - {$s->name} (grade: " . ($s->gradeLevel->year ?? '') . ") is_special=" . ($s->is_special? '1':'0') . "\n";
    $entries = ScheduleEntry::where('section_id',$s->id)->orderBy('period')->get();
    foreach($entries as $e){
        $sub = $e->subject_id ? Subject::find($e->subject_id) : null;
        $type = $sub ? ($sub->type ?? '') : '';
        echo "  P{$e->period}: ";
        if($sub){ echo "{$sub->id} ({$sub->code}) {$sub->name} [type={$type}]"; } else echo "<empty>";
        echo "\n";
    }
}
