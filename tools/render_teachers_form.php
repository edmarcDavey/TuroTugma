<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\GradeLevel;

$subjects = Subject::orderBy('name')->get();
$gradeLevels = GradeLevel::orderBy('name')->get();

// render the blade partial
$view = view('admin.it.teachers._form', compact('subjects','gradeLevels'));
$html = $view->render();
file_put_contents(__DIR__.'/rendered_teachers_form.html', $html);
echo "Wrote rendered_teachers_form.html (size=".strlen($html).")\n";
