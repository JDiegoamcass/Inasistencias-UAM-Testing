<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profesor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfesorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Carlos',
            'apellido' => 'Pérez',
            'email' => 'carlos.profesor@uam.edu.ni',
            'password' => Hash::make('12345678'),
            'role' => 'profesor',
            'cif' => 'CPF-001',
            'carrera' => 'Ingeniería de Sistemas',
        ]);

        Profesor::create([
            'user_id' => $user->id,
            'apellido' => $user->apellido,
            'cif' => $user->cif,
            'facultad' => 'Ingeniería de Sistemas',
        ]);
    }
}
