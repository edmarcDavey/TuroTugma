<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class CleanupSeededTeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $query = Teacher::query();

        // Remove demo teachers (staff_id starting with demo- or email starting with demo.)
        $query->where('staff_id', 'like', 'demo-%')
            ->orWhere('email', 'like', 'demo.%')
            // also remove any teacher records that match the name "Anne Salve" (case-insensitive fragment)
            ->orWhere('name', 'like', '%Anne Salve%');

        $count = $query->count();

        if ($count === 0) {
            $this->command->info('No seeded/demo teachers found.');
            return;
        }

        $query->delete();

        $this->command->info("Removed {$count} seeded/demo teacher(s).");
    }
}
