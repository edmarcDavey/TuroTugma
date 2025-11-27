<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;

$teachers = Teacher::with(['subjects','gradeLevels'])->get();
$missingGrades = [];
$missingSubjects = [];

foreach ($teachers as $t) {
    if (!($t->gradeLevels && $t->gradeLevels->count())) $missingGrades[] = "{$t->id}: {$t->name}";
    if (!($t->subjects && $t->subjects->count())) $missingSubjects[] = "{$t->id}: {$t->name}";
}

echo "Total teachers: " . $teachers->count() . "\n";
echo "Teachers without grade levels: " . count($missingGrades) . "\n";
if (count($missingGrades)) { echo implode("\n", $missingGrades) . "\n"; }

echo "Teachers without subjects: " . count($missingSubjects) . "\n";
if (count($missingSubjects)) { echo implode("\n", $missingSubjects) . "\n"; }

return 0;
