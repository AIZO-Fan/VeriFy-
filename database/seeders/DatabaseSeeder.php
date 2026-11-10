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
        // 1. Akun Admin VeriFy
        User::updateOrCreate(
            ['email' => 'admin@verify.id'],
            [
                'name' => 'Administrator VeriFy',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Test User
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // 3. Akun Demo User Biasa
        User::updateOrCreate(
            ['email' => 'user@verify.id'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // 4. Panggil Seeder Modul Baru
        $this->call([
            ModuleSeeder::class,
        ]);

        // 5. Jalankan seeder simulasi skenario jika ada
        if (class_exists(SimulationScenarioSeeder::class)) {
            $this->call([
                SimulationScenarioSeeder::class,
            ]);
        }
    }
}