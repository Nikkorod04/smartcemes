<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create CESO Admin (Extension Director)
        User::create([
            'name' => 'Dexter Lynce',
            'email' => 'cesoadmin@lnu.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        // Create CESO Secretary
        User::create([
            'name' => 'Maria Santos',
            'email' => 'cesosecretary@lnu.com',
            'password' => Hash::make('Secretary@123'),
            'role' => 'secretary',
        ]);

        // Seed communities
        $this->call(CommunitySeeder::class);

        // Seed extension programs
        $this->call(ExtensionProgramSeeder::class);

        // Seed Almagro assessment summaries
        $this->call(AlmagroAssessmentSeeder::class);
    }
}
