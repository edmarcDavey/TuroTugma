<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\Teacher;

$spa = Subject::where('code','SPA')->orWhere('name','like','%Special Program in the Arts%')->first();
$spj = Subject::where('code','SPJ')->orWhere('name','like','%Special Program in Journalism%')->first();

if (!$spa) { echo "SPA subject not found\n"; exit(1); }
if (!$spj) { echo "SPJ subject not found\n"; exit(1); }

// Candidate teacher IDs selected for SPA and SPJ
$spaTeacherIds = [3, 12, 70];
$spjTeacherIds = [10, 18, 51];

// Attach without detaching existing relations
foreach ($spaTeacherIds as $tid) {
    $t = Teacher::find($tid);
    if ($t) {
        $t->subjects()->syncWithoutDetaching([$spa->id]);
        echo "Attached teacher {$t->id} - {$t->name} to SPA (subject_id={$spa->id})\n";
    } else {
        echo "Teacher id {$tid} not found\n";
    }
}

foreach ($spjTeacherIds as $tid) {
    $t = Teacher::find($tid);
    if ($t) {
        $t->subjects()->syncWithoutDetaching([$spj->id]);
        echo "Attached teacher {$t->id} - {$t->name} to SPJ (subject_id={$spj->id})\n";
    } else {
        echo "Teacher id {$tid} not found\n";
    }
}

echo "Done.\n";
