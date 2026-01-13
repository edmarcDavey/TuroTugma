<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeLevel;
use App\Models\Section;

class ExampleSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = GradeLevel::where('school_stage', 'junior')->orderBy('year')->get();

        foreach ($grades as $grade) {
            // Create two sample sections per grade (A and B)
            foreach (['A','B'] as $i => $suffix) {
                Section::updateOrCreate(
                    ['grade_level_id' => $grade->id, 'name' => $grade->name . "-" . $suffix],
                    [
                        'code' => strtoupper(substr($grade->name,0,2)) . ($i+1),
                        'ordinal' => $i + 1,
                        'capacity' => 40,
                        'school_year' => date('Y'),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
