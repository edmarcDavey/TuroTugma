<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subject;

echo "=== Subject Coverage Check ===\n\n";

$subjects = Subject::withCount('teachers')->get();

foreach ($subjects as $subject) {
    echo "{$subject->name}: {$subject->teachers_count} teachers\n";
}

echo "\n";

$noTeachers = Subject::doesntHave('teachers')->get();

if ($noTeachers->count() > 0) {
    echo "⚠️ Subjects with NO teachers:\n";
    foreach ($noTeachers as $subject) {
        echo "  - {$subject->name}\n";
    }
} else {
    echo "✅ All subjects have at least one teacher assigned!\n";
}
