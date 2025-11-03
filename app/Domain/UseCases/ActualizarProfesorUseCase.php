<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ProfesorRepositoryInterface;

class ActualizarProfesorUseCase
{
    public function __construct(
        private ProfesorRepositoryInterface $profesorRepository
    ) {}

    public function execute(int $profesorId, string $apellido): bool
    {
        $profesor = $this->profesorRepository->findById($profesorId);

        if (!$profesor) {
            return false;
        }

        $profesor->setApellido($apellido);

        return $this->profesorRepository->update($profesor);
    }
}
