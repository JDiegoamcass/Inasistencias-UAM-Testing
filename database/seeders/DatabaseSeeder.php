<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario de prueba completo
        User::factory()->create([
            'name' => 'Test User',
            'apellido' => 'Ejemplo',
            'email' => 'tes5@example.com',
            'cif' => '133456559',
            'carrera' => 'Ingeniería de Sistemas',
        ]);

        // Seeder del profesor
        $this->call([
            ProfesorSeeder::class,
        ]);
    }
}
