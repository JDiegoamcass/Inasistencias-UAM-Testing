<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Estudiante;

interface EstudianteRepositoryInterface
{
    public function findAll(): array;
    
    public function findById(int $id): ?Estudiante;
    
    public function findByUserId(int $userId): ?Estudiante;
    
    public function findByCif(string $cif): ?Estudiante;
    
    public function save(Estudiante $estudiante): Estudiante;
    
    public function update(Estudiante $estudiante): bool;
    
    public function delete(int $id): bool;
}
