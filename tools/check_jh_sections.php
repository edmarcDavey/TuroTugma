<?php
// Simple script to check grade_levels and sections, and count Junior High sections.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

$grades = DB::table('grade_levels')->select('id','name','school_stage')->orderBy('id')->get();
echo "GRADE_LEVELS:\n".json_encode($grades, JSON_PRETTY_PRINT)."\n\n";

$sections = DB::select('select s.id,s.name,s.grade_level_id,gl.name as grade_name,gl.school_stage from sections s join grade_levels gl on s.grade_level_id=gl.id order by s.grade_level_id, s.ordinal');
echo "SECTIONS (first 50):\n".json_encode(array_slice($sections,0,50), JSON_PRETTY_PRINT)."\n\n";

$count = DB::table('sections')
    ->join('grade_levels','sections.grade_level_id','=','grade_levels.id')
    ->where('grade_levels.school_stage','junior')
    ->count();
echo "JUNIOR_SECTION_COUNT: $count\n";
