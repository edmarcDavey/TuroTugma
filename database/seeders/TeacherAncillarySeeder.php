<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherAncillarySeeder extends Seeder
{
    public function run(): void
    {
        $ancillaryRoles = [
            'School Paper Adviser',
            'Sports Coordinator',
            'Athletic Moderator',
            'Science Fair Coordinator',
            'Math Fair Coordinator',
            'English Fair Coordinator',
            'Youth Formation Program Adviser',
            'Supreme Student Government Adviser',
            'Boy Scouts Coordinator',
            'Girl Scouts Coordinator',
            'YES-O Adviser',
            'Brigada Eskwela Coordinator',
            'Gender and Development (GAD) Focal Person',
            'Disaster Risk Reduction and Management (DRRM) Coordinator',
            'School Discipline Committee Member',
            'Guidance Committee Member',
            'Child Protection Committee Member',
            'School-Based Management (SBM) Committee Member',
            'ICT Coordinator',
            'Learning Resource Coordinator',
            'Testing Coordinator',
            'Research/Innovation Coordinator',
        ];

        $firstNames = [
            'Maria', 'Jose', 'Juan', 'Ana', 'Pedro', 'Rosa', 'Miguel', 'Elena', 'Carlos', 'Sofia', 'Ramon', 'Carmen',
            'Luis', 'Isabella', 'Antonio', 'Luisa', 'Francisco', 'Catalina', 'Jorge', 'Beatriz', 'Ricardo', 'Gabriela',
            'Manuel', 'Patricia', 'Roberto', 'Teresa', 'Fernando', 'Monica', 'Eduardo', 'Cristina', 'Rafael', 'Margarita',
            'Sergio', 'Andrea', 'Pablo', 'Diana', 'Alberto', 'Lorena', 'Oscar', 'Valeria', 'Rodrigo', 'Cecilia', 'Mario',
            'Natalia', 'Julio', 'Adriana', 'Arturo', 'Veronica', 'Victor', 'Silvia', 'Andres', 'Laura', 'Raul', 'Claudia',
            'Javier', 'Gloria', 'Felipe', 'Irene', 'Enrique', 'Angelica', 'Daniel', 'Josefina', 'Gabriel', 'Rosario',
            'Alejandro', 'Dolores', 'Marcos', 'Pilar', 'Leonardo', 'Mercedes'
        ];

        $lastNames = [
            'Reyes', 'Santos', 'Cruz', 'Bautista', 'Garcia', 'Mendoza', 'Torres', 'Gonzales', 'Lopez', 'Rodriguez', 'Hernandez',
            'Perez', 'Sanchez', 'Ramirez', 'Flores', 'Rivera', 'Gomez', 'Diaz', 'Morales', 'Martinez', 'Dela Cruz', 'Villanueva',
            'Aquino', 'Ramos', 'Castillo', 'Fernandez', 'Gutierrez', 'Ocampo', 'De Leon', 'Pascual', 'Santiago', 'Navarro',
            'Mercado', 'Tolentino', 'Valdez', 'Soriano', 'Castro', 'Miranda', 'Aguilar', 'Salazar', 'Campos', 'Jimenez',
            'Dela Rosa', 'Medina', 'Rojas', 'Vargas', 'Luna', 'Guerrero', 'Estrada', 'Ortiz'
        ];

        $designations = config('teachers.designations', ['Teacher I']);
        $statuses = config('teachers.statuses', ['Permanent']);
        $sexes = ['male', 'female'];

        echo "Seeding 68 teachers (Teacher model) with ancillary assignments..." . PHP_EOL;

        for ($i = 1; $i <= 68; $i++) {
            $first = $firstNames[array_rand($firstNames)];
            $last = $lastNames[array_rand($lastNames)];
            $fullName = "$first $last";

            $ancillary = (rand(1, 100) <= 30) ? $ancillaryRoles[array_rand($ancillaryRoles)] : null;

            Teacher::create([
                'staff_id' => 'T-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'name' => $fullName,
                'sex' => $sexes[array_rand($sexes)],
                'email' => strtolower(str_replace(' ', '.', $fullName)) . '@dnhs.edu.ph',
                'designation' => $designations[array_rand($designations)],
                'status_of_appointment' => $statuses[array_rand($statuses)],
                'ancillary_assignments' => $ancillary,
                'course_degree' => null,
                'course_major' => null,
                'course_minor' => null,
                'number_handled_per_week' => null,
                'advisory' => null,
                'contact' => null,
                'max_load_per_week' => null,
                'max_load_per_day' => null,
                'availability' => null,
                'preferences' => null,
                'notes' => null,
            ]);
        }

        $total = Teacher::count();
        $withAncillary = Teacher::whereNotNull('ancillary_assignments')->count();

        echo "Summary:" . PHP_EOL;
        echo "  Total teachers (Teacher table): {$total}" . PHP_EOL;
        echo "  With ancillary assignments: {$withAncillary}" . PHP_EOL;
    }
}
