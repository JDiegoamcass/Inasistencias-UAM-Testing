<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\ClassRepositoryInterface;
use App\Models\Clase as ClassEloquent;

class GetAllClassesUseCase
{
    public function __construct(
        private ClassRepositoryInterface $classRepository
    ) {}

    public function execute()
    {
        // For Blade view compatibility, return Eloquent models
        return ClassEloquent::with('profesor.user')->get();
    }
}
