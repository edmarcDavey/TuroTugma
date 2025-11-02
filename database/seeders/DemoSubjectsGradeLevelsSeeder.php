<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\GradeLevel;

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

        // Demo subjects creation removed to avoid seeded subjects in demo seeder.

        // Do not auto-create grade levels here; grade levels will be added by admins.

        // Note: demo teacher creation removed to avoid seeded teachers in environments.
    }
}
