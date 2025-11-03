<?php

namespace App\Infrastructure\DataSources;

use App\Domain\Models\Estudiante;
use App\Domain\Repositories\StudentRepositoryInterface;
use App\Models\Estudiante as EstudianteEloquent;

class StudentDataSource implements StudentRepositoryInterface
{
    private function toDomain(EstudianteEloquent $eloquent): Estudiante
    {
        return new Estudiante(
            userId: $eloquent->user_id,
            apellido: $eloquent->apellido,
            cif: $eloquent->cif,
            carrera: $eloquent->carrera,
            id: $eloquent->id
        );
    }

    private function toEloquent(Estudiante $estudiante): array
    {
        return [
            'user_id' => $estudiante->getUserId(),
            'apellido' => $estudiante->getApellido(),
            'cif' => $estudiante->getCif(),
            'carrera' => $estudiante->getCarrera(),
        ];
    }

    public function findAll(): array
    {
        return EstudianteEloquent::all()
            ->map(fn($eloquent) => $this->toDomain($eloquent))
            ->toArray();
    }

    public function findById(int $id): ?Estudiante
    {
        $eloquent = EstudianteEloquent::find($id);
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function findByUserId(int $userId): ?Estudiante
    {
        $eloquent = EstudianteEloquent::where('user_id', $userId)->first();
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function findByCif(string $cif): ?Estudiante
    {
        $eloquent = EstudianteEloquent::where('cif', $cif)->first();
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function save(Estudiante $estudiante): Estudiante
    {
        $eloquent = EstudianteEloquent::create($this->toEloquent($estudiante));
        
        return $this->toDomain($eloquent);
    }

    public function update(Estudiante $estudiante): bool
    {
        if (!$estudiante->getId()) {
            return false;
        }

        $eloquent = EstudianteEloquent::find($estudiante->getId());
        
        if (!$eloquent) {
            return false;
        }

        $eloquent->update($this->toEloquent($estudiante));
        
        return true;
    }

    public function delete(int $id): bool
    {
        $eloquent = EstudianteEloquent::find($id);
        
        if (!$eloquent) {
            return false;
        }

        return $eloquent->delete();
    }
}
