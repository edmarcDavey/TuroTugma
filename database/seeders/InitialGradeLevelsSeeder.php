<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeLevel;

class InitialGradeLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            ['name' => 'Grade 7', 'year' => 7, 'school_stage' => 'junior', 'section_naming' => '', 'section_naming_options' => ['planned_sections' => 0]],
            ['name' => 'Grade 8', 'year' => 8, 'school_stage' => 'junior', 'section_naming' => '', 'section_naming_options' => ['planned_sections' => 0]],
            ['name' => 'Grade 9', 'year' => 9, 'school_stage' => 'junior', 'section_naming' => '', 'section_naming_options' => ['planned_sections' => 0]],
            ['name' => 'Grade 10', 'year' => 10, 'school_stage' => 'junior', 'section_naming' => '', 'section_naming_options' => ['planned_sections' => 0]],
            ['name' => 'Grade 11', 'year' => 11, 'school_stage' => 'senior', 'section_naming' => '', 'section_naming_options' => ['planned_sections' => 0]],
            ['name' => 'Grade 12', 'year' => 12, 'school_stage' => 'senior', 'section_naming' => '', 'section_naming_options' => ['planned_sections' => 0]],
        ];

        foreach ($grades as $g) {
            GradeLevel::updateOrCreate(
                ['name' => $g['name']],
                [
                    'year' => $g['year'],
                    'school_stage' => $g['school_stage'],
                    'section_naming' => $g['section_naming'],
                    'section_naming_options' => $g['section_naming_options'],
                ]
            );
        }
    }
}
