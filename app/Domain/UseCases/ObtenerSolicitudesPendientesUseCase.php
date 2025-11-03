<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\SolicitudRepositoryInterface;
use App\Models\Solicitud as SolicitudEloquent;

class ObtenerSolicitudesPendientesUseCase
{
    public function __construct(
        private SolicitudRepositoryInterface $solicitudRepository
    ) {}

    public function execute()
    {
        // Para compatibilidad con vistas Blade, devolvemos modelos Eloquent
        return SolicitudEloquent::with('user')
            ->where(function($query) {
                $query->whereNull('estado')
                      ->orWhere('estado', 'pendiente');
            })
            ->get();
    }
}
