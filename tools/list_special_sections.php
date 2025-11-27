<?php
require __DIR__ . '/../vendor/autoload.php';

// Boot Laravel app
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Section;

$sections = Section::orderBy('id')->get();
$found = false;
foreach ($sections as $s) {
    if ($s->is_special) {
        $found = true;
        echo "ID {$s->id} - {$s->name} (grade_level_id={$s->grade_level_id}) => is_special=1\n";
    }
}
if (!$found) echo "No sections with is_special=1 found.\n";
