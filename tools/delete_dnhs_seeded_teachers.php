<?php
// tools/delete_dnhs_seeded_teachers.php
// Bootstraps Laravel and deletes seeded teachers created earlier by the Filipino seeder (email @dnhs.edu.ph).

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

// Criteria: staff_id starts with 'T' and email contains '@dnhs.edu.ph'
$teachers = Teacher::where('staff_id', 'like', 'T%')
    ->where('email', 'like', '%@dnhs.edu.ph')
    ->get();

if ($teachers->isEmpty()) {
    echo "No matching seeded teachers found (staff_id like 'T%' and email @dnhs.edu.ph).\n";
    exit(0);
}

$deleted = 0;
DB::beginTransaction();
try {
    foreach ($teachers as $t) {
        $id = $t->id;
        $sid = $t->staff_id;
        $name = $t->name;
        // detach relations (subjects, grade levels)
        try { $t->subjects()->detach(); } catch (Exception $e) {}
        try { $t->gradeLevels()->detach(); } catch (Exception $e) {}
        $t->delete();
        $deleted++;
        echo "Deleted: {$sid} - {$name}\n";
    }
    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    echo "Failed to delete seeded teachers: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Done. Deleted {$deleted} teacher(s).\n";

return 0;
