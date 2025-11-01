<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ItCoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@example.com';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User();
            $user->name = 'IT Coordinator';
            $user->email = $email;
            // password cast will hash when saving (password cast exists), but to be explicit:
            $user->password = bcrypt('password');
            $user->role = 'it_coordinator';
            $user->save();
        } else {
            $user->role = 'it_coordinator';
            $user->save();
        }
    }
}
