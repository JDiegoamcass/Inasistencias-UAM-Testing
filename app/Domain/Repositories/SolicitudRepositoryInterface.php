<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Solicitud;

interface SolicitudRepositoryInterface
{
    public function findAll(): array;
    
    public function findById(int $id): ?Solicitud;
    
    public function findByUserId(int $userId): array;
    
    public function findPendientes(): array;
    
    public function save(Solicitud $solicitud): Solicitud;
    
    public function update(Solicitud $solicitud): bool;
    
    public function delete(int $id): bool;
}
