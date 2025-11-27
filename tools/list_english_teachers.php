<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;

$subjects = Subject::where('name','like','%English%')->get();
if($subjects->isEmpty()){
    echo "No subjects matching 'English' found.\n";
    exit(0);
}

foreach($subjects as $s){
    echo "Subject: {$s->id} - {$s->name}\n";
    $teachers = $s->teachers()->orderBy('name')->get();
    if($teachers->isEmpty()){
        echo "  (no teachers attached)\n";
        continue;
    }
    foreach($teachers as $t){
        echo "  - {$t->id}: {$t->name}\n";
    }
}
