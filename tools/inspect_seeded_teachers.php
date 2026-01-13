<?php
// tools/inspect_seeded_teachers.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;

$teachers = Teacher::where('staff_id', 'like', 'T%')
    ->where('email', 'like', '%@dnhs.edu.ph')
    ->orderBy('staff_id')
    ->take(20)
    ->get();

if ($teachers->isEmpty()) {
    echo "No seeded teachers found matching criteria.\n";
    exit(0);
}

foreach ($teachers as $t) {
    $subs = $t->subjects()->pluck('name')->toArray();
    $grades = $t->gradeLevels()->pluck('name')->toArray();
    printf("%s | %s | %s | %s | %s | %s | subjects:%d grades:%d\n",
        $t->staff_id,
        $t->name,
        $t->sex,
        $t->email,
        $t->contact,
        $t->designation . '/' . $t->status_of_appointment,
        count($subs), count($grades)
    );
    echo "  Subjects: " . implode(', ', $subs) . "\n";
    echo "  Grades: " . implode(', ', $grades) . "\n";
}

// summary counts per subject
// subject counts among seeded teachers only
$subCounts = [];
foreach (\App\Models\Subject::all() as $s) {
    $subCounts[$s->name] = \DB::table('teacher_subject')
        ->where('subject_id', $s->id)
        ->whereIn('teacher_id', function($q){
            $q->select('id')->from('teachers')->where('staff_id', 'like', 'T%')->where('email', 'like', '%@dnhs.edu.ph');
        })->count();
}

echo "\nSubject counts (seeded teachers only):\n";
foreach ($subCounts as $name => $c) {
    echo " - $name: $c\n";
}

return 0;
