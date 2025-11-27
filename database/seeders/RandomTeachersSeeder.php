<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\GradeLevel;
use App\Models\Subject;
use Illuminate\Support\Str;

class RandomTeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $juniorLevels = GradeLevel::where('school_stage', 'junior')->get();
        $seniorLevels = GradeLevel::where('school_stage', 'senior')->get();
        $allSubjects = Subject::orderBy('name')->get();

        if ($juniorLevels->isEmpty() || $seniorLevels->isEmpty()) {
            $this->command->warn('Grade levels for junior or senior stage not found. Run InitialGradeLevelsSeeder first.');
        }

        if ($allSubjects->isEmpty()) {
            $this->command->warn('No subjects found — teachers will be created without subject assignments.');
        }

        $this->createStageTeachers(30, $juniorLevels, $allSubjects, $faker, 'junior');
        $this->createStageTeachers(38, $seniorLevels, $allSubjects, $faker, 'senior');

        $this->command->info('Random teachers seeding complete.');
    }

    protected function createStageTeachers(int $count, $gradeLevels, $subjects, $faker, string $stage)
    {
        // Filipino name pools
        $maleFirst = ['Juan','Jose','Mark','Ramon','Carlos','Miguel','Antonio','Rogelio','Roberto','Emilio','Ramon','Daniel','Rodolfo','Luis','Eduardo','Fernando','Alfredo','Nestor','Victor','Michael','Renato'];
        $femaleFirst = ['Maria','Ana','Ligaya','Carmela','Rosa','Gloria','Liza','May','Christine','Patricia','Nicole','Sofia','Isabel','Evelyn','Grace','Janet','Miriam','Lourdes','Imelda','Karen'];
        $surnames = ['Dela Cruz','Santos','Reyes','Cruz','Garcia','Mendoza','Bautista','Ramos','Gonzales','Fernandez','Aquino','Soriano','Uy','Dela Rosa','Valdez','Lopez','Villanueva','Delos Santos','Ortiz','Navarro'];

        for ($i = 0; $i < $count; $i++) {
            // build a Filipino-style name
            $sex = $faker->randomElement(['male','female']);
            $first = $sex === 'male' ? $faker->randomElement($maleFirst) : $faker->randomElement($femaleFirst);
            // sometimes include a middle initial
            $middle = $faker->boolean(40) ? ' ' . chr(65 + $faker->numberBetween(0, 25)) . '.' : '';
            $last = $faker->randomElement($surnames);
            $name = trim("{$first}{$middle} {$last}");
            $email = $faker->unique()->safeEmail();
            $staff = strtoupper('T' . str_pad((string)$faker->numberBetween(1000, 9999), 4, '0', STR_PAD_LEFT));

            $teacher = Teacher::create([
                'name' => $name,
                'email' => $email,
                'staff_id' => $staff,
                'sex' => $faker->randomElement(['male', 'female']),
                'contact' => $faker->phoneNumber(),
                'designation' => $faker->randomElement(['Teacher I', 'Teacher II', 'Subject Coordinator', 'Instructor', 'Head Teacher']),
                'status_of_appointment' => $faker->randomElement(['permanent', 'temporary', 'contractual']),
                'course_degree' => $faker->randomElement(['B.Ed', 'BS', 'MA', 'MS', null]),
                'course_major' => $faker->word(),
                'course_minor' => $faker->word(),
                'number_handled_per_week' => $faker->numberBetween(0, 8),
                'advisory' => '',
            ]);

            // attach 1-2 grade levels within this stage (if available)
            if ($gradeLevels->isNotEmpty()) {
                $maxGrades = min(2, $gradeLevels->count());
                $numGrades = $faker->numberBetween(1, max(1, $maxGrades));
                $pick = $gradeLevels->random($numGrades);
                $ids = $pick instanceof \Illuminate\Support\Collection ? $pick->pluck('id')->toArray() : [$pick->id];
                $teacher->gradeLevels()->sync($ids);
            }

            // attach 2-5 random subjects related to the selected grade levels / stage
            if ($subjects->isNotEmpty()) {
                // map our stage to subject.stage values (seed subjects likely use 'jhs'/'shs')
                $subjectStageKey = $stage === 'junior' ? 'jhs' : 'shs';
                $eligible = collect();
                // prefer subjects attached to any of the teacher's grade levels
                if (!empty($ids)) {
                    // prefer subjects linked to the chosen grade levels
                    $eligible = \App\Models\Subject::whereHas('gradeLevels', function($q) use ($ids){
                        $q->whereIn('grade_levels.id', $ids);
                    })->orderBy('name')->get();
                }
                // if still few, include subjects that match the stage key
                if ($eligible->count() < 2) {
                        $stageMatches = $subjects->filter(function($s) use ($subjectStageKey) {
                            return isset($s->stage) && strtolower($s->stage) === strtolower($subjectStageKey);
                        });
                        $eligible = $eligible->merge($stageMatches)->unique('id');
                }
                // fallback to any subjects if still insufficient
                if ($eligible->count() < 2) {
                    $eligible = $subjects;
                }

                $numSubjects = $faker->numberBetween(2, min(5, max(2, $eligible->count())));
                $sel = $eligible->random($numSubjects);
                $teacher->subjects()->sync(collect($sel)->pluck('id')->toArray());
            }
        }
    }
}
