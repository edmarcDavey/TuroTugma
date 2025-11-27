<?php
// Update teacher names to English-first + Spanish-surname style
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;

echo "Starting teacher name update...\n";

$firstNames = ['James','John','Michael','David','Robert','William','Christopher','Mark','Paul','Andrew','Thomas','Daniel','Matthew','Anthony','Joshua','Ryan','Kevin','Jason','Eric','Brian','Samuel','Peter','Adam','Ethan','Noah'];
$surnames = ['Garcia','Hernandez','Rodriguez','Martinez','Lopez','Gonzalez','Perez','Sanchez','Ramirez','Torres','Flores','Rivera','Gomez','Diaz','Reyes','Morales','Cruz','Ramos','Navarro','Castillo','Dominguez','Ortiz','Vargas','Mendoza','Silva'];

$teachers = Teacher::all();
$count = $teachers->count();
if ($count === 0) {
    echo "No teachers found.\n";
    exit(0);
}

$i = 0;
foreach ($teachers as $t) {
    $i++;
    $first = $firstNames[array_rand($firstNames)];
    // sometimes include a middle initial (30% chance)
    $middle = (random_int(1, 100) <= 30) ? ' ' . chr(65 + random_int(0, 25)) . '.' : '';
    $last = $surnames[array_rand($surnames)];
    $newName = trim("{$first}{$middle} {$last}");

    $old = $t->name;
    $t->name = $newName;
    $t->save();
    echo "[{$i}/{$count}] {$old} -> {$newName}\n";
}

echo "Updated {$count} teacher names.\n";

return 0;
