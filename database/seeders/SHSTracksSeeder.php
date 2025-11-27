<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\GradeLevel;

class SHSTracksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shsGradeIds = GradeLevel::whereBetween('year', [11,12])->pluck('id')->toArray();

        $tracks = [
            'STEM' => [
                'Pre-Calculus',
                'Physics',
                'Chemistry',
            ],
            'ABM' => [
                'Business Math',
                'Marketing',
            ],
            'HUMSS' => [
                'Philippine Politics',
                'Creative Writing',
            ],
            'TVL' => [
                'Cookery',
                'Electrical Installation',
                'Computer Programming',
            ],
        ];

        foreach ($tracks as $track => $subjects) {
            foreach ($subjects as $name) {
                $searchName = strtolower(trim($name));
                $subject = Subject::whereRaw('LOWER(name) = ?', [$searchName])->first();
                if (!$subject) {
                    $subject = Subject::create([
                        'code' => null,
                        'name' => $name,
                        'type' => strtolower($track),
                        'track' => $track,
                        'stage' => 'senior_high',
                    ]);
                    $this->command->info("Created subject: {$name} ({$track})");
                } else {
                    // update type/track/stage if needed
                    $updated = false;
                    if (($subject->type ?? '') !== strtolower($track)) { $subject->type = strtolower($track); $updated = true; }
                    if (($subject->track ?? '') !== $track) { $subject->track = $track; $updated = true; }
                    if (($subject->stage ?? '') !== 'senior_high') { $subject->stage = 'senior_high'; $updated = true; }
                    if ($updated) { $subject->save(); $this->command->info("Updated subject metadata: {$name} -> {$track}"); }
                    else { $this->command->info("Subject exists: {$name} ({$track})"); }
                }

                if (!empty($shsGradeIds)) {
                    $subject->gradeLevels()->syncWithoutDetaching($shsGradeIds);
                    $this->command->info("Attached to Senior High grades: " . implode(',', $shsGradeIds));
                } else {
                    $this->command->warn('No Senior High grade levels found; attach manually.');
                }
            }
        }
    }
}
