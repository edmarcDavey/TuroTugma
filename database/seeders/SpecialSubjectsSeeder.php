<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\GradeLevel;

class SpecialSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jhsGradeIds = GradeLevel::whereBetween('year', [7,10])->pluck('id')->toArray();

        $items = [
            ['code' => 'SPA', 'name' => 'SPA - Special Program in the Arts', 'type' => 'special subjects'],
            ['code' => 'SPJ', 'name' => 'SPJ - Special Program in Journalism', 'type' => 'special subjects'],
        ];

        foreach ($items as $it) {
            $subject = Subject::where('code', $it['code'])->orWhereRaw('LOWER(name) = ?', [strtolower($it['name'])])->first();
            if (!$subject) {
                $subject = Subject::create([
                    'code' => $it['code'],
                    'name' => $it['name'],
                    'type' => $it['type'] ?? null,
                ]);
                $this->command->info("Created subject: {$it['code']} - {$it['name']}");
            } else {
                $this->command->info("Subject exists: {$subject->code} - {$subject->name}");
            }

            if (!empty($jhsGradeIds)) {
                $subject->gradeLevels()->syncWithoutDetaching($jhsGradeIds);
                $this->command->info("Attached to Junior High grades: " . implode(',', $jhsGradeIds));
            } else {
                $this->command->warn('No Junior High grade levels found; attach manually.');
            }
        }
    }
}
