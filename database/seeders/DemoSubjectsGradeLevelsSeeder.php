<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Teacher;

class DemoSubjectsGradeLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            ['code'=>'MATH','name'=>'Mathematics'],
            ['code'=>'SCI','name'=>'Science'],
            ['code'=>'ENG','name'=>'English'],
            ['code'=>'PE','name'=>'Physical Education'],
            ['code'=>'HIST','name'=>'History'],
        ];

        foreach ($subjects as $s) {
            Subject::firstOrCreate(['code' => $s['code']], ['name' => $s['name']]);
        }

        // Do not auto-create grade levels here; grade levels will be added by admins.

        // Create a demo teacher if none exists
        $teacher = Teacher::firstOrCreate(
            ['staff_id' => 'demo-100'],
            [
                'staff_id' => 'demo-100',
                'name' => 'Demo Teacher',
                'email' => 'demo.teacher@example.com',
                'contact' => '09171234567',
                'max_load_per_week' => 25,
                'max_load_per_day' => 5,
                'availability' => [],
            ]
        );

        // Attach first two subjects and first grade level
        $subjectIds = Subject::orderBy('id')->limit(2)->pluck('id')->toArray();
        $gradeIds = GradeLevel::orderBy('id')->limit(1)->pluck('id')->toArray();

        $teacher->subjects()->sync($subjectIds);
        $teacher->gradeLevels()->sync($gradeIds);
    }
}
