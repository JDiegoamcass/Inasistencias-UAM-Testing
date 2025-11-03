<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Clase;

interface ClassRepositoryInterface
{
    public function findAll(): array;
    
    public function findById(int $id): ?Clase;
    
    public function findByCodigo(string $codigo): ?Clase;
    
    public function findByProfesorId(int $profesorId): array;
    
    public function save(Clase $clase): Clase;
    
    public function update(Clase $clase): bool;
    
    public function delete(int $id): bool;
}
