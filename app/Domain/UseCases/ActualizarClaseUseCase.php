<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ClaseRepositoryInterface;

class ActualizarClaseUseCase
{
    public function __construct(
        private ClaseRepositoryInterface $claseRepository
    ) {}

    public function execute(
        int $claseId,
        string $nombre,
        string $codigo,
        int $profesorId,
        ?string $horario = null
    ): bool {
        $clase = $this->claseRepository->findById($claseId);

        if (!$clase) {
            return false;
        }

        // Validar que el código no exista en otra clase
        $claseConCodigo = $this->claseRepository->findByCodigo($codigo);
        
        if ($claseConCodigo && $claseConCodigo->getId() !== $claseId) {
            throw new \InvalidArgumentException('El código de clase ya está en uso por otra clase');
        }

        $clase->setNombre($nombre);
        $clase->setCodigo($codigo);
        $clase->setProfesorId($profesorId);
        $clase->setHorario($horario);

        return $this->claseRepository->update($clase);
    }
}
