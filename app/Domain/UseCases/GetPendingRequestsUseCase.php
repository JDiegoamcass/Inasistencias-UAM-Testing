<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\RequestRepositoryInterface;
use App\Models\Solicitud as SolicitudEloquent;

class GetPendingRequestsUseCase
{
    public function __construct(
        private RequestRepositoryInterface $requestRepository
    ) {}

    public function execute()
    {
        // For Blade view compatibility, return Eloquent models
        return SolicitudEloquent::with('user')
            ->where(function($query) {
                $query->whereNull('estado')
                      ->orWhere('estado', 'pendiente');
            })
            ->get();
    }
}
