<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeachersWithAncillarySeeder extends Seeder
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
            'Maria', 'Jose', 'Juan', 'Ana', 'Pedro', 'Rosa', 'Miguel', 'Elena',
            'Carlos', 'Sofia', 'Ramon', 'Carmen', 'Luis', 'Isabella', 'Antonio',
            'Luisa', 'Francisco', 'Catalina', 'Jorge', 'Beatriz', 'Ricardo',
            'Gabriela', 'Manuel', 'Patricia', 'Roberto', 'Teresa', 'Fernando',
            'Monica', 'Eduardo', 'Cristina', 'Rafael', 'Margarita', 'Sergio',
            'Andrea', 'Pablo', 'Diana', 'Alberto', 'Lorena', 'Oscar', 'Valeria',
            'Rodrigo', 'Cecilia', 'Mario', 'Natalia', 'Julio', 'Adriana',
            'Arturo', 'Veronica', 'Victor', 'Silvia', 'Andres', 'Laura',
            'Raul', 'Claudia', 'Javier', 'Gloria', 'Felipe', 'Irene',
            'Enrique', 'Angelica', 'Daniel', 'Josefina', 'Gabriel', 'Rosario',
            'Alejandro', 'Dolores', 'Marcos', 'Pilar', 'Leonardo', 'Mercedes'
        ];

        $lastNames = [
            'Reyes', 'Santos', 'Cruz', 'Bautista', 'Garcia', 'Mendoza', 'Torres',
            'Gonzales', 'Lopez', 'Rodriguez', 'Hernandez', 'Perez', 'Sanchez',
            'Ramirez', 'Flores', 'Rivera', 'Gomez', 'Diaz', 'Morales', 'Martinez',
            'Dela Cruz', 'Villanueva', 'Aquino', 'Ramos', 'Castillo', 'Fernandez',
            'Gutierrez', 'Ocampo', 'De Leon', 'Pascual', 'Santiago', 'Navarro',
            'Mercado', 'Tolentino', 'Valdez', 'Soriano', 'Castro', 'Miranda',
            'Aguilar', 'Salazar', 'Campos', 'Jimenez', 'Dela Rosa', 'Medina',
            'Rojas', 'Vargas', 'Luna', 'Guerrero', 'Estrada', 'Ortiz'
        ];

        echo 'Creating 68 teachers...' . PHP_EOL;

        for ($i = 1; $i <= 68; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            
            $specialAssignment = (rand(1, 100) <= 30) ? $ancillaryRoles[array_rand($ancillaryRoles)] : null;
            
            User::create([
                'name' => $fullName,
                'email' => strtolower(str_replace(' ', '.', $fullName)) . '@dnhs.edu.ph',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'special_assignment' => $specialAssignment,
            ]);

            if ($specialAssignment) {
                echo "   $fullName - $specialAssignment" . PHP_EOL;
            }
        }

        echo PHP_EOL . 'Summary:' . PHP_EOL;
        $total = User::where('role', 'teacher')->count();
        $withAncillary = User::where('role', 'teacher')->whereNotNull('special_assignment')->count();
        
        echo "  Total teachers: $total" . PHP_EOL;
        echo "  With ancillary: $withAncillary" . PHP_EOL;
    }
}
