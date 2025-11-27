<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;

$keywords = ['art','arts','music','dance','media','journal','creative','writing','film','theatre','drama','instrumental'];

$teachers = Teacher::with('subjects')->orderBy('id')->get();

foreach ($teachers as $t) {
    $matches = [];
    foreach ($t->subjects as $s) {
        foreach ($keywords as $k) {
            if (stripos($s->name, $k) !== false) { $matches[] = $s->name; break; }
        }
    }
    if (count($matches)) {
        echo "{$t->id} | {$t->name} -> " . implode(', ', array_unique($matches)) . "\n";
    }
}

echo "--- All teachers (id:name) ---\n";
foreach ($teachers as $t) { echo "{$t->id}: {$t->name}\n"; }
