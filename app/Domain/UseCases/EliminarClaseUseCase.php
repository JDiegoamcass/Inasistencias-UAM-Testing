<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ClaseRepositoryInterface;

class EliminarClaseUseCase
{
    public function __construct(
        private ClaseRepositoryInterface $claseRepository
    ) {}

    public function execute(int $claseId): bool
    {
        $clase = $this->claseRepository->findById($claseId);

        if (!$clase) {
            return false;
        }

        return $this->claseRepository->delete($claseId);
    }
}
