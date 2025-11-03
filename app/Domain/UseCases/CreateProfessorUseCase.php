<?php

namespace App\Domain\UseCases;

use App\Domain\Models\Profesor;
use App\Domain\Repositories\ProfessorRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateProfessorUseCase
{
    public function __construct(
        private ProfessorRepositoryInterface $professorRepository
    ) {}

    public function execute(
        string $nombre,
        string $apellido,
        string $email,
        string $cif,
        string $facultad,
        string $password
    ): Profesor {
        // Validate that email doesn't exist
        if (User::where('email', $email)->exists()) {
            throw new \InvalidArgumentException('Email is already registered');
        }

        // Validate that CIF doesn't exist
        if ($this->professorRepository->findByCif($cif)) {
            throw new \InvalidArgumentException('CIF is already registered');
        }

        // Create user
        $user = User::create([
            'name' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => Hash::make($password),
            'cif' => $cif,
            'carrera' => $facultad,
            'role' => 'profesor'
        ]);

        // Create professor
        $profesor = new Profesor(
            userId: $user->id,
            apellido: $apellido,
            cif: $cif,
            facultad: $facultad
        );

        return $this->professorRepository->save($profesor);
    }
}
