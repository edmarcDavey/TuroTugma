<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class CleanupSeededSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Names seeded by SubjectsSeeder
        $jhs = ['Mathematics','Science','English','Filipino','Araling Panlipunan','Edukasyon sa Pagpapakatao','MAPEH'];
        $tles = ['Cookery','Carpentry','Electrical Installation','Information and Communications Technology'];
        $spa = ['Media Arts','Visual Arts','Instrumental Music','Dance Arts','Vocal Music','Theater Arts','Creative Writing'];
        $shs = ['Oral Communication','Reading and Writing','General Mathematics','Pre-Calculus','Physics','Chemistry'];
        $others = ['Journalism'];

        $names = array_merge($jhs, $tles, $spa, $shs, $others);

        $subjects = Subject::whereIn('name', $names)->get();

        if ($subjects->isEmpty()) {
            $this->command->info('No seeded subjects found to remove.');
            return;
        }

        $count = 0;
        foreach ($subjects as $s) {
            // Detach relations to avoid FK issues
            if (method_exists($s, 'gradeLevels')) {
                $s->gradeLevels()->detach();
            }
            if (method_exists($s, 'teachers')) {
                $s->teachers()->detach();
            }
            $s->delete();
            $count++;
        }

        $this->command->info("Removed {$count} seeded subject(s).");
    }
}
