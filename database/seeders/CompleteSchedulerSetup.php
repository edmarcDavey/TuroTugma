<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Section;

class CompleteSchedulerSetup extends Seeder
{
    public function run()
    {
        $this->command->info('Setting up complete scheduler data...');
        
        // 1. Ensure SchedulingConfig and DayConfigs exist
        $this->call(SchedulingConfigSeeder::class);
        $this->command->info("✓ Scheduling config and day configs created");
        
        // 2. Create Junior High subjects
        $jhGrades = GradeLevel::where('school_stage', 'junior')->pluck('id')->toArray();
        
        $jhSubjects = [
            ['code' => 'ENG', 'name' => 'English', 'type' => 'core'],
            ['code' => 'FIL', 'name' => 'Filipino', 'type' => 'core'],
            ['code' => 'MATH', 'name' => 'Mathematics', 'type' => 'core'],
            ['code' => 'SCI', 'name' => 'Science', 'type' => 'core'],
            ['code' => 'AP', 'name' => 'Araling Panlipunan', 'type' => 'core'],
            ['code' => 'MAPEH', 'name' => 'MAPEH', 'type' => 'core'],
            ['code' => 'TLE', 'name' => 'Technology and Livelihood Education', 'type' => 'core'],
            ['code' => 'ESP', 'name' => 'Edukasyon sa Pagpapakatao', 'type' => 'core'],
            ['code' => 'SPA', 'name' => 'SPA - Special Program in the Arts', 'type' => 'special subjects'],
            ['code' => 'SPJ', 'name' => 'SPJ - Special Program in Journalism', 'type' => 'special subjects'],
        ];
        
        foreach ($jhSubjects as $sub) {
            $subject = Subject::updateOrCreate(
                ['code' => $sub['code']],
                ['name' => $sub['name'], 'type' => $sub['type']]
            );
            
            if (!empty($jhGrades)) {
                $subject->gradeLevels()->syncWithoutDetaching($jhGrades);
            }
            
            $this->command->info("✓ Subject: {$sub['code']} - {$sub['name']}");
        }
        
        // 3. Create sections
        $this->call(ExampleSectionsSeeder::class);
        
        // 4. Create teachers
        $this->call(FilipinoTeachersSeeder::class);
        
        $this->command->info('✅ Complete setup finished!');
    }
}
