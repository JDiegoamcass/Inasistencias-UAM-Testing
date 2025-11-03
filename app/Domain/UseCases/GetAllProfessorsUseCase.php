<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ProfessorRepositoryInterface;
use App\Models\Profesor as ProfessorEloquent;

class GetAllProfessorsUseCase
{
    public function __construct(
        private ProfessorRepositoryInterface $professorRepository
    ) {}

    public function execute()
    {
        // For Blade view compatibility, return Eloquent models
        return ProfessorEloquent::with('user')->get();
    }
}
