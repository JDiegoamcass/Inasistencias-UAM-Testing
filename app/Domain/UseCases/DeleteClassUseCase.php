<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ClassRepositoryInterface;

class DeleteClassUseCase
{
    public function __construct(
        private ClassRepositoryInterface $classRepository
    ) {}

    public function execute(int $classId): bool
    {
        $clase = $this->classRepository->findById($classId);

        if (!$clase) {
            return false;
        }

        return $this->classRepository->delete($classId);
    }
}
