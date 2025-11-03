<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ProfessorRepositoryInterface;

class UpdateProfessorUseCase
{
    public function __construct(
        private ProfessorRepositoryInterface $professorRepository
    ) {}

    public function execute(int $professorId, string $apellido): bool
    {
        $profesor = $this->professorRepository->findById($professorId);

        if (!$profesor) {
            return false;
        }

        $profesor->setApellido($apellido);

        return $this->professorRepository->update($profesor);
    }
}
