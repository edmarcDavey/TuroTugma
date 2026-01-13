<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\GradeLevel;
use Faker\Factory as Faker;

class FilipinoTeachersSeeder extends Seeder
{
    public function run()
    {
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

        $subjects = Subject::all();
        $gradeLevels = GradeLevel::all();

        if ($subjects->isEmpty() || $gradeLevels->isEmpty()) {
            $this->command->warn('No subjects or grade levels found — skipping FilipinoTeachersSeeder.');
            return;
        }

        $designations = config('teachers.designations', ['Teacher']);
        $statuses = config('teachers.statuses', ['Regular']);

        // identify junior grade ids heuristically
        $junior = $gradeLevels->filter(function($g){
            if (isset($g->year) && in_array((int)$g->year, [7,8,9,10])) return true;
            if (stripos($g->name ?? '', 'grade 7') !== false) return true;
            return false;
        })->pluck('id')->toArray();
        if (empty($junior)) {
            $junior = $gradeLevels->take(min(4, $gradeLevels->count()))->pluck('id')->toArray();
        }
        $allGradeIds = $gradeLevels->pluck('id')->toArray();
        $senior = array_values(array_diff($allGradeIds, $junior));

        $totalToCreate = 68;
        $minPerSubject = 3;
        $maxPerSubject = 7;

        $subjectIds = $subjects->pluck('id')->toArray();
        $subjectCounts = array_fill_keys($subjectIds, 0);

        // gather existing names to avoid collisions and track used names
        $existingNames = Teacher::pluck('name')->map(function($n){
            return strtolower(trim($n));
        })->toArray();
        $usedNames = $existingNames;

        // helper to create a unique full name
        $makeUniqueName = function($base) use (&$usedNames, $faker) {
            $name = $base;
            $key = strtolower(trim($name));
            $attempt = 0;
            while(in_array($key, $usedNames) && $attempt < 10) {
                $attempt++;
                // try adding a middle initial
                $mi = chr(65 + $faker->numberBetween(0,25));
                $name = $base . ' ' . $mi . '.';
                $key = strtolower(trim($name));
            }
            if (in_array($key, $usedNames)) {
                $suf = 1;
                while(in_array($key . ' #' . $suf, $usedNames)) $suf++;
                $name = $name . ' #' . $suf;
                $key = strtolower(trim($name));
            }
            $usedNames[] = $key;
            return $name;
        };

        // If there are many subjects, adjust minPerSubject so we can fit
        if (count($subjectIds) * $minPerSubject > $totalToCreate) {
            $minPerSubject = max(1, (int) floor($totalToCreate / count($subjectIds)));
            $this->command->warn("Adjusted minPerSubject to {$minPerSubject} to fit {$totalToCreate} teachers.");
        }

        $created = 0;

        // Step A: guarantee minPerSubject for each subject
        foreach ($subjects as $sub) {
            while ($subjectCounts[$sub->id] < $minPerSubject && $created < $totalToCreate) {
                $base = $faker->randomElement($firstNames) . ' ' . $faker->randomElement($lastNames);
                $name = ($makeUniqueName)($base);
                $staffId = 'T' . str_pad($created + 1, 4, '0', STR_PAD_LEFT);
                $email = Str::slug(strtolower($name)) . '.' . $staffId . '@dnhs.edu.ph';
                $contact = '+63' . '9' . (string) $faker->numberBetween(100000000, 999999999);

                $teacher = Teacher::create([
                    'staff_id' => $staffId,
                    'name' => $name,
                    'sex' => $faker->randomElement(['male','female']),
                    'email' => $email,
                    'contact' => $contact,
                    'designation' => is_array($designations) ? $faker->randomElement($designations) : 'Teacher',
                    'status_of_appointment' => is_array($statuses) ? $faker->randomElement($statuses) : 'Regular',
                ]);

                // primary subject
                $teacher->subjects()->attach([$sub->id]);

                // assign grade levels (prefer junior for common subjects)
                $preferJunior = preg_match('/math|science|english|filipino|language|pe|health|mapeh/i', $sub->name);
                $assign = $preferJunior ? $junior : ($senior ?: $junior);
                $teacher->gradeLevels()->attach($assign);

                $subjectCounts[$sub->id]++;
                $created++;
            }
        }

        // Step B: fill remaining teachers, pick subjects below max
        $subjectQueue = $subjectIds;
        while ($created < $totalToCreate) {
            // shuffle to distribute
            shuffle($subjectQueue);
            $picked = null;
            foreach ($subjectQueue as $sid) {
                if ($subjectCounts[$sid] < $maxPerSubject) { $picked = $sid; break; }
            }
            if ($picked === null) break; // nothing available

            $sub = $subjects->firstWhere('id', $picked);
            $base = $faker->randomElement($firstNames) . ' ' . $faker->randomElement($lastNames);
            $name = ($makeUniqueName)($base);
            $staffId = 'T' . str_pad($created + 1, 4, '0', STR_PAD_LEFT);
            $email = Str::slug(strtolower($name)) . '.' . $staffId . '@dnhs.edu.ph';
            $contact = '+63' . '9' . (string) $faker->numberBetween(100000000, 999999999);

            $teacher = Teacher::create([
                'staff_id' => $staffId,
                'name' => $name,
                'sex' => $faker->randomElement(['male','female']),
                'email' => $email,
                'contact' => $contact,
                'designation' => is_array($designations) ? $faker->randomElement($designations) : 'Teacher',
                'status_of_appointment' => is_array($statuses) ? $faker->randomElement($statuses) : 'Regular',
            ]);

            $teacher->subjects()->attach([$sub->id]);
            $preferJunior = preg_match('/math|science|english|filipino|language|pe|health|mapeh/i', $sub->name);
            $assign = $preferJunior ? $junior : ($senior ?: $junior);
            $teacher->gradeLevels()->attach($assign);

            $subjectCounts[$sub->id]++;
            $created++;
        }

        // Final report
        foreach ($subjectCounts as $sid => $cnt) {
            $s = $subjects->firstWhere('id', $sid);
            $this->command->info(($s->name ?? 'Unknown') . ": $cnt");
        }

        $this->command->info("Seeded $created Filipino teachers.");
    }
}
