<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ProfessorRepositoryInterface;

class DeleteProfessorUseCase
{
    public function __construct(
        private ProfessorRepositoryInterface $professorRepository
    ) {}

    public function execute(int $professorId): bool
    {
        $profesor = $this->professorRepository->findById($professorId);

        if (!$profesor) {
            return false;
        }

        return $this->professorRepository->delete($professorId);
    }
}
