<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;

$q = $argv[1] ?? 'Filipino';
$subs = Subject::where('name','like','%'.$q.'%')->get();
if(!count($subs)){
    echo "No subjects matched for {$q}\n";
    exit(0);
}
foreach($subs as $s){
    echo "{$s->id} => {$s->name}\n";
}
