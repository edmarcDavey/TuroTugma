<?php
// tools/update_filipino_names.php
// Bootstraps Laravel and updates existing seeded FT teachers to Filipino-style names.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;
use App\Models\GradeLevel;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

$faker = Faker::create('en_PH');

$firstNames = [
    'Jose','Juan','Marianne','Maria','Ramon','Liza','Carlos','Ana','Miguel','Rosa',
    'Mark','Michael','Josefa','Emmanuel','Angel','Kristine','Lea','Jomar','Rafael','Dina',
    'Josefina','Alvin','Bernadette','Crisanto','Danilo','Evelyn','Florentino','Gloria','Hector','Imelda'
];
$lastNames = [
    'Santos','Reyes','Cruz','Dela Cruz','Ramos','Gonzales','Garcia','Lopez','Rodriguez','Torres',
    'Dizon','Delos Santos','Flores','Mendoza','Navarro','Aquino','Silva','Carpio','Guevara','Manalo'
];

// Select teachers likely created by the seeder: staff_id starting with T (we updated earlier)
// and email containing the example domain used by the seeder.
$teachers = Teacher::where('staff_id', 'like', 'T%')
    ->where('email', 'like', '%@example.edu.ph')
    ->get();

if ($teachers->isEmpty()) {
    echo "No matching FT seeded teachers found to update.\n";
    exit(0);
}

$updated = 0;
foreach ($teachers as $t) {
    // generate Filipino-style name
    // generate Filipino-style name (force curated pools for consistent Filipino names)
    $name = $faker->randomElement($firstNames) . ' ' . $faker->randomElement($lastNames);

    $emailLocal = Str::slug(strtolower($name));
    // extract numeric suffix from existing staff_id (e.g. FT0002 -> 2) to keep stable numbering
    $suffix = preg_replace('/[^0-9]/', '', $t->staff_id);
    if (empty($suffix)) $suffix = (string)random_int(1,9999);
    // new employee id format: T followed by zero-padded 4 digits (e.g. T0023)
    $newStaffId = 'T' . str_pad(intval($suffix), 4, '0', STR_PAD_LEFT);

    // update email to include numeric suffix for uniqueness (preserve earlier pattern)
    $email = $emailLocal . $suffix . '@example.edu.ph';

    // generate Philippine mobile in +63 9XXXXXXXXX format (10 digits after +63, starting with 9)
    $mobileNine = random_int(100000000, 999999999); // 9 digits
    $contact = '+63' . '9' . $mobileNine;

    $t->staff_id = $newStaffId;
    $t->name = $name;
    $t->email = $email;
    $t->contact = $contact;
    // ensure designation and status_of_appointment match config values used in the form
    try {
        $designations = config('teachers.designations', []);
        $statuses = config('teachers.statuses', []);
        $t->designation = is_array($designations) && count($designations) ? $designations[0] : ($t->designation ?? 'Teacher I');
        $t->status_of_appointment = is_array($statuses) && count($statuses) ? $statuses[0] : ($t->status_of_appointment ?? 'Permanent');
    } catch (Exception $e) {
        // if config unavailable, fallback to safe defaults
        $t->designation = $t->designation ?? 'Teacher I';
        $t->status_of_appointment = $t->status_of_appointment ?? 'Permanent';
    }

    try {
        $t->save();
        // Ensure grade level assignments are exclusive: junior (7-10) OR senior (11-12), not both
        try {
            $allGrades = GradeLevel::all();
            $juniorIds = $allGrades->filter(function($g){
                if (isset($g->year) && in_array((int)$g->year, [7,8,9,10])) return true;
                $name = $g->name ?? '';
                if (stripos($name, 'grade 7') !== false || stripos($name, 'grade 8') !== false || stripos($name, 'grade 9') !== false || stripos($name, 'grade 10') !== false) return true;
                // also accept 'junior' keyword
                if (stripos($name, 'junior') !== false) return true;
                return false;
            })->pluck('id')->toArray();

            $seniorIds = $allGrades->filter(function($g){
                if (isset($g->year) && in_array((int)$g->year, [11,12])) return true;
                $name = $g->name ?? '';
                if (stripos($name, 'grade 11') !== false || stripos($name, 'grade 12') !== false) return true;
                if (stripos($name, 'senior') !== false || stripos($name, 'shs') !== false) return true;
                return false;
            })->pluck('id')->toArray();

            $current = $t->gradeLevels()->pluck('id')->toArray();
            $hasJunior = count(array_intersect($current, $juniorIds));
            $hasSenior = count(array_intersect($current, $seniorIds));

            if ($hasJunior && $hasSenior) {
                // decide which side to keep: prefer the side with more assigned grades, else keep junior
                $junCount = count(array_intersect($current, $juniorIds));
                $senCount = count(array_intersect($current, $seniorIds));
                if ($junCount >= $senCount) {
                    $keep = array_values(array_intersect($current, $juniorIds));
                } else {
                    $keep = array_values(array_intersect($current, $seniorIds));
                }
                if (!empty($keep)) {
                    $t->gradeLevels()->sync($keep);
                }
            }
        } catch (\Exception $e) {
            // ignore grade-level normalization errors
        }
        $updated++;
        echo "Updated: {$t->staff_id} -> {$name} ({$email})\n";
    } catch (Exception $e) {
        echo "Failed to update {$t->staff_id}: {$e->getMessage()}\n";
    }
}

echo "Done. Updated {$updated} teacher(s).\n";

return 0;
