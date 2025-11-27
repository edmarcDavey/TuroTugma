<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;

$spa = Subject::where('code','SPA')->first();
$spj = Subject::where('code','SPJ')->first();

if ($spa) {
    echo "SPA (id={$spa->id}) - {$spa->name}\n";
    foreach ($spa->teachers as $t) {
        echo "  - {$t->id}: {$t->name}\n";
    }
} else { echo "SPA not found\n"; }

if ($spj) {
    echo "SPJ (id={$spj->id}) - {$spj->name}\n";
    foreach ($spj->teachers as $t) {
        echo "  - {$t->id}: {$t->name}\n";
    }
} else { echo "SPJ not found\n"; }
