<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SecretariaAcademica;

class SecretariaSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Secretaria Prueba',
            'apellido' => 'UAM',
            'email' => 'secretaria@uam.edu.ni',
            'password' => bcrypt('12345678'),
            'cif' => 'SEC999',
            'carrera' => 'Gestión Académica',
        ]);

        SecretariaAcademica::create([
            'user_id' => $user->id,
            'apellido' => $user->apellido,
            'cif' => $user->cif,
        ]);
    }
}

