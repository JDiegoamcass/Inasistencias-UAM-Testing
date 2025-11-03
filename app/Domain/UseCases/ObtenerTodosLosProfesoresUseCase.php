<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Models\Profesor as ProfesorEloquent;

class ObtenerTodosLosProfesoresUseCase
{
    public function __construct(
        private ProfesorRepositoryInterface $profesorRepository
    ) {}

    public function execute()
    {
        // Para compatibilidad con vistas Blade, devolvemos modelos Eloquent
        return ProfesorEloquent::with('user')->get();
    }
}
