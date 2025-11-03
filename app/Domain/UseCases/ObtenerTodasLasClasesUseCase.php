<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ClaseRepositoryInterface;
use App\Models\Clase as ClaseEloquent;

class ObtenerTodasLasClasesUseCase
{
    public function __construct(
        private ClaseRepositoryInterface $claseRepository
    ) {}

    public function execute()
    {
        // Para compatibilidad con vistas Blade, devolvemos modelos Eloquent
        return ClaseEloquent::with('profesor.user')->get();
    }
}
