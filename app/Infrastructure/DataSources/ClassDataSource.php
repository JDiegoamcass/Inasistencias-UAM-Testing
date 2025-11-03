<?php

namespace App\Infrastructure\DataSources;

use App\Domain\Models\Clase;
use App\Domain\Repositories\ClassRepositoryInterface;
use App\Models\Clase as ClaseEloquent;

class ClassDataSource implements ClassRepositoryInterface
{
    private function toDomain(ClaseEloquent $eloquent): Clase
    {
        return new Clase(
            nombre: $eloquent->nombre,
            codigo: $eloquent->codigo,
            profesorId: $eloquent->profesor_id,
            horario: $eloquent->horario,
            id: $eloquent->id
        );
    }

    private function toEloquent(Clase $clase): array
    {
        return [
            'nombre' => $clase->getNombre(),
            'codigo' => $clase->getCodigo(),
            'profesor_id' => $clase->getProfesorId(),
            'horario' => $clase->getHorario(),
        ];
    }

    public function findAll(): array
    {
        return ClaseEloquent::all()
            ->map(fn($eloquent) => $this->toDomain($eloquent))
            ->toArray();
    }

    public function findById(int $id): ?Clase
    {
        $eloquent = ClaseEloquent::find($id);
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function findByCodigo(string $codigo): ?Clase
    {
        $eloquent = ClaseEloquent::where('codigo', $codigo)->first();
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function findByProfesorId(int $profesorId): array
    {
        return ClaseEloquent::where('profesor_id', $profesorId)
            ->get()
            ->map(fn($eloquent) => $this->toDomain($eloquent))
            ->toArray();
    }

    public function save(Clase $clase): Clase
    {
        $eloquent = ClaseEloquent::create($this->toEloquent($clase));
        
        return $this->toDomain($eloquent);
    }

    public function update(Clase $clase): bool
    {
        if (!$clase->getId()) {
            return false;
        }

        $eloquent = ClaseEloquent::find($clase->getId());
        
        if (!$eloquent) {
            return false;
        }

        $eloquent->update($this->toEloquent($clase));
        
        return true;
    }

    public function delete(int $id): bool
    {
        $eloquent = ClaseEloquent::find($id);
        
        if (!$eloquent) {
            return false;
        }

        return $eloquent->delete();
    }
}
