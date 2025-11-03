<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ClassRepositoryInterface;

class UpdateClassUseCase
{
    public function __construct(
        private ClassRepositoryInterface $classRepository
    ) {}

    public function execute(
        int $classId,
        string $nombre,
        string $codigo,
        int $profesorId,
        ?string $horario = null
    ): bool {
        $clase = $this->classRepository->findById($classId);

        if (!$clase) {
            return false;
        }

        // Validate that code doesn't exist in another class
        $classWithCode = $this->classRepository->findByCodigo($codigo);
        
        if ($classWithCode && $classWithCode->getId() !== $classId) {
            throw new \InvalidArgumentException('Class code is already in use by another class');
        }

        $clase->setNombre($nombre);
        $clase->setCodigo($codigo);
        $clase->setProfesorId($profesorId);
        $clase->setHorario($horario);

        return $this->classRepository->update($clase);
    }
}
