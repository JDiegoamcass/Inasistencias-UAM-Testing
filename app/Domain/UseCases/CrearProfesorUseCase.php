<?php

namespace App\Domain\UseCases;

use App\Domain\Models\Profesor;
use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CrearProfesorUseCase
{
    public function __construct(
        private ProfesorRepositoryInterface $profesorRepository
    ) {}

    public function execute(
        string $nombre,
        string $apellido,
        string $email,
        string $cif,
        string $facultad,
        string $password
    ): Profesor {
        // Validar que el email no exista
        if (User::where('email', $email)->exists()) {
            throw new \InvalidArgumentException('El email ya está registrado');
        }

        // Validar que el CIF no exista
        if ($this->profesorRepository->findByCif($cif)) {
            throw new \InvalidArgumentException('El CIF ya está registrado');
        }

        // Crear usuario
        $user = User::create([
            'name' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => Hash::make($password),
            'cif' => $cif,
            'carrera' => $facultad,
            'role' => 'profesor'
        ]);

        // Crear profesor
        $profesor = new Profesor(
            userId: $user->id,
            apellido: $apellido,
            cif: $cif,
            facultad: $facultad
        );

        return $this->profesorRepository->save($profesor);
    }
}
