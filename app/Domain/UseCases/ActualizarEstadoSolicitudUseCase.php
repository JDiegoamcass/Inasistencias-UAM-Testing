<?php

namespace App\Domain\UseCases;

use App\Domain\Models\Solicitud;
use App\Domain\Repositories\SolicitudRepositoryInterface;

class ActualizarEstadoSolicitudUseCase
{
    public function __construct(
        private SolicitudRepositoryInterface $solicitudRepository
    ) {}

    public function execute(int $solicitudId, string $estado, ?string $resolucion = null): bool
    {
        $solicitud = $this->solicitudRepository->findById($solicitudId);

        if (!$solicitud) {
            return false;
        }

        if (!in_array($estado, ['aprobado', 'rechazado'])) {
            throw new \InvalidArgumentException('Estado inválido. Debe ser "aprobado" o "rechazado"');
        }

        if ($estado === 'aprobado') {
            $solicitud->aprobar($resolucion);
        } else {
            $solicitud->rechazar($resolucion);
        }

        return $this->solicitudRepository->update($solicitud);
    }
}
