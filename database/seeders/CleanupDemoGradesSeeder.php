<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeLevel;

class CleanupDemoGradesSeeder extends Seeder
{
    /**
     * Remove demo grade levels (Grade 7..Grade 10) if present.
     * Run with: php artisan db:seed --class=CleanupDemoGradesSeeder
     */
    public function run()
    {
        $names = ['Grade 7','Grade 8','Grade 9','Grade 10'];
        GradeLevel::whereIn('name', $names)->get()->each(function($g){
            $g->sections()->delete();
            $g->delete();
        });
        $this->command->info('Removed demo grade levels: ' . implode(', ', $names));
    }
}
