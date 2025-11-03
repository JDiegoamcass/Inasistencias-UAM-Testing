<?php

namespace App\Domain\UseCases;

use App\Domain\Models\Clase;
use App\Domain\Repositories\ClaseRepositoryInterface;

class CrearClaseUseCase
{
    public function __construct(
        private ClaseRepositoryInterface $claseRepository
    ) {}

    public function execute(
        string $nombre,
        string $codigo,
        int $profesorId,
        ?string $horario = null
    ): Clase {
        // Validar que el código no exista
        $claseExistente = $this->claseRepository->findByCodigo($codigo);
        
        if ($claseExistente) {
            throw new \InvalidArgumentException('El código de clase ya existe');
        }

        $clase = new Clase(
            nombre: $nombre,
            codigo: $codigo,
            profesorId: $profesorId,
            horario: $horario
        );

        return $this->claseRepository->save($clase);
    }
}
