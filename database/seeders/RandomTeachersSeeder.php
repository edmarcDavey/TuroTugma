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
        for ($i = 0; $i < $count; $i++) {
            $name = $faker->name();
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
                $pick = $gradeLevels->random(min(2, $gradeLevels->count()));
                $ids = $pick instanceof \Illuminate\Support\Collection ? $pick->pluck('id')->toArray() : [$pick->id];
                $teacher->gradeLevels()->sync($ids);
            }

            // attach 2-6 random subjects (if available)
            if ($subjects->isNotEmpty()) {
                $num = $faker->numberBetween(2, min(6, $subjects->count()));
                $sel = $subjects->random($num);
                $teacher->subjects()->sync(collect($sel)->pluck('id')->toArray());
            }
        }
    }
}
