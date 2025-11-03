<?php

namespace App\Infrastructure\DataSources;

use App\Domain\Models\Profesor;
use App\Domain\Repositories\ProfessorRepositoryInterface;
use App\Models\Profesor as ProfesorEloquent;

class ProfessorDataSource implements ProfessorRepositoryInterface
{
    private function toDomain(ProfesorEloquent $eloquent): Profesor
    {
        return new Profesor(
            userId: $eloquent->user_id,
            apellido: $eloquent->apellido,
            cif: $eloquent->cif,
            facultad: $eloquent->facultad,
            id: $eloquent->id
        );
    }

    private function toEloquent(Profesor $profesor): array
    {
        return [
            'user_id' => $profesor->getUserId(),
            'apellido' => $profesor->getApellido(),
            'cif' => $profesor->getCif(),
            'facultad' => $profesor->getFacultad(),
        ];
    }

    public function findAll(): array
    {
        return ProfesorEloquent::all()
            ->map(fn($eloquent) => $this->toDomain($eloquent))
            ->toArray();
    }

    public function findById(int $id): ?Profesor
    {
        $eloquent = ProfesorEloquent::find($id);
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function findByUserId(int $userId): ?Profesor
    {
        $eloquent = ProfesorEloquent::where('user_id', $userId)->first();
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function findByCif(string $cif): ?Profesor
    {
        $eloquent = ProfesorEloquent::where('cif', $cif)->first();
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function save(Profesor $profesor): Profesor
    {
        $eloquent = ProfesorEloquent::create($this->toEloquent($profesor));
        
        return $this->toDomain($eloquent);
    }

    public function update(Profesor $profesor): bool
    {
        if (!$profesor->getId()) {
            return false;
        }

        $eloquent = ProfesorEloquent::find($profesor->getId());
        
        if (!$eloquent) {
            return false;
        }

        $eloquent->update($this->toEloquent($profesor));
        
        return true;
    }

    public function delete(int $id): bool
    {
        $eloquent = ProfesorEloquent::find($id);
        
        if (!$eloquent) {
            return false;
        }

        return $eloquent->delete();
    }
}
