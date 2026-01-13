<?php

namespace Database\Seeders;

use App\Models\Strand;
use Illuminate\Database\Seeder;

class StrandSeeder extends Seeder
{
    public function run(): void
    {
        $strands = [
            ['name' => 'STEM', 'code' => 'stem', 'description' => 'Science, Technology, Engineering, and Mathematics'],
            ['name' => 'ABM', 'code' => 'abm', 'description' => 'Accountancy, Business and Management'],
            ['name' => 'HUMSS', 'code' => 'humss', 'description' => 'Humanities and Social Sciences'],
            ['name' => 'ICT', 'code' => 'ict', 'description' => 'Information and Communications Technology'],
            ['name' => 'HE', 'code' => 'he', 'description' => 'Home Economics'],
        ];

        foreach ($strands as $strand) {
            Strand::firstOrCreate(['code' => $strand['code']], $strand);
        }
    }
}
