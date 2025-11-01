<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\GradeLevel;

class SubjectsSeeder extends Seeder
{
    public function run()
    {
        // Junior High core
        $jhs = ['Mathematics','Science','English','Filipino','Araling Panlipunan','Edukasyon sa Pagpapakatao','MAPEH'];
        foreach ($jhs as $name) {
            $s = Subject::firstOrCreate(['code' => strtoupper(substr($name,0,4)), 'name' => $name], ['type' => 'jhs_core']);
            // Attach to Grades 7-10 if present
            $grades = GradeLevel::whereIn('name',['Grade 7','Grade 8','Grade 9','Grade 10'])->pluck('id')->toArray();
            if (!empty($grades)) $s->gradeLevels()->sync($grades);
        }

        // Example TLE/TVL
        $tles = ['Cookery','Carpentry','Electrical Installation','Information and Communications Technology'];
        foreach ($tles as $name) {
            $s = Subject::firstOrCreate(['code'=>strtoupper(substr($name,0,4)),'name'=>$name], ['type'=>'tle']);
            $grades = GradeLevel::whereIn('name',['Grade 9','Grade 10'])->pluck('id')->toArray();
            if (!empty($grades)) $s->gradeLevels()->sync($grades);
        }

        // SPA examples
        $spa = ['Media Arts','Visual Arts','Instrumental Music','Dance Arts','Vocal Music','Theater Arts','Creative Writing'];
        foreach ($spa as $name) {
            $s = Subject::firstOrCreate(['code'=>strtoupper(substr($name,0,4)),'name'=>$name], ['type'=>'spa']);
        }

        // Journalism
        Subject::firstOrCreate(['code'=>'JOUR','name'=>'Journalism'], ['type'=>'journalism']);

        // A few SHS examples
        $shs = [
            ['code'=>'OCOM','name'=>'Oral Communication','type'=>'shs_core'],
            ['code'=>'READ','name'=>'Reading and Writing','type'=>'shs_core'],
            ['code'=>'GENM','name'=>'General Mathematics','type'=>'shs_core'],
            ['code'=>'PREC','name'=>'Pre-Calculus','type'=>'shs_strand'],
            ['code'=>'PHYS','name'=>'Physics','type'=>'shs_strand'],
            ['code'=>'CHEM','name'=>'Chemistry','type'=>'shs_strand'],
        ];
        foreach ($shs as $row) {
            Subject::firstOrCreate(['code'=>$row['code'],'name'=>$row['name']], ['type'=>$row['type']]);
        }
    }
}
