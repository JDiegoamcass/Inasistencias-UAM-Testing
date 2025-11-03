<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Profesor;

interface ProfesorRepositoryInterface
{
    public function findAll(): array;
    
    public function findById(int $id): ?Profesor;
    
    public function findByUserId(int $userId): ?Profesor;
    
    public function findByCif(string $cif): ?Profesor;
    
    public function save(Profesor $profesor): Profesor;
    
    public function update(Profesor $profesor): bool;
    
    public function delete(int $id): bool;
}
