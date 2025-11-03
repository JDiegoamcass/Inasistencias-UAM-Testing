<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ProfesorRepositoryInterface;

class EliminarProfesorUseCase
{
    public function __construct(
        private ProfesorRepositoryInterface $profesorRepository
    ) {}

    public function execute(int $profesorId): bool
    {
        $profesor = $this->profesorRepository->findById($profesorId);

        if (!$profesor) {
            return false;
        }

        return $this->profesorRepository->delete($profesorId);
    }
}
