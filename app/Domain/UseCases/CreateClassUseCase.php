<?php

namespace App\Domain\UseCases;

use App\Domain\Models\Clase;
use App\Domain\Repositories\ClassRepositoryInterface;

class CreateClassUseCase
{
    public function __construct(
        private ClassRepositoryInterface $classRepository
    ) {}

    public function execute(
        string $nombre,
        string $codigo,
        int $profesorId,
        ?string $horario = null
    ): Clase {
        // Validate that code doesn't exist
        $existingClass = $this->classRepository->findByCodigo($codigo);
        
        if ($existingClass) {
            throw new \InvalidArgumentException('Class code already exists');
        }

        $clase = new Clase(
            nombre: $nombre,
            codigo: $codigo,
            profesorId: $profesorId,
            horario: $horario
        );

        return $this->classRepository->save($clase);
    }
}
