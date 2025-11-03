<?php

namespace App\Infrastructure\DataSources;

use App\Domain\Models\Solicitud;
use App\Domain\Repositories\SolicitudRepositoryInterface;
use App\Models\Solicitud as SolicitudEloquent;

class SolicitudDataSource implements SolicitudRepositoryInterface
{
    private function toDomain(SolicitudEloquent $eloquent): Solicitud
    {
        return new Solicitud(
            userId: $eloquent->user_id,
            comentario: $eloquent->comentario,
            estado: $eloquent->estado,
            evidencia: $eloquent->evidencia,
            fechaSolicitud: $eloquent->fechaSolicitud,
            fechaAusencia: $eloquent->fechaAusencia,
            resolucion: $eloquent->resolucion,
            tipoAusencia: $eloquent->tipoAusencia,
            id: $eloquent->id
        );
    }

    private function toEloquent(Solicitud $solicitud): array
    {
        return [
            'user_id' => $solicitud->getUserId(),
            'comentario' => $solicitud->getComentario(),
            'estado' => $solicitud->getEstado(),
            'evidencia' => $solicitud->getEvidencia(),
            'fechaSolicitud' => $solicitud->getFechaSolicitud(),
            'fechaAusencia' => $solicitud->getFechaAusencia(),
            'resolucion' => $solicitud->getResolucion(),
            'tipoAusencia' => $solicitud->getTipoAusencia(),
        ];
    }

    public function findAll(): array
    {
        return SolicitudEloquent::all()
            ->map(fn($eloquent) => $this->toDomain($eloquent))
            ->toArray();
    }

    public function findById(int $id): ?Solicitud
    {
        $eloquent = SolicitudEloquent::find($id);
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function findByUserId(int $userId): array
    {
        return SolicitudEloquent::where('user_id', $userId)
            ->get()
            ->map(fn($eloquent) => $this->toDomain($eloquent))
            ->toArray();
    }

    public function findPendientes(): array
    {
        return SolicitudEloquent::whereNull('estado')
            ->orWhere('estado', 'pendiente')
            ->get()
            ->map(fn($eloquent) => $this->toDomain($eloquent))
            ->toArray();
    }

    public function save(Solicitud $solicitud): Solicitud
    {
        $eloquent = SolicitudEloquent::create($this->toEloquent($solicitud));
        
        return $this->toDomain($eloquent);
    }

    public function update(Solicitud $solicitud): bool
    {
        if (!$solicitud->getId()) {
            return false;
        }

        $eloquent = SolicitudEloquent::find($solicitud->getId());
        
        if (!$eloquent) {
            return false;
        }

        $eloquent->update($this->toEloquent($solicitud));
        
        return true;
    }

    public function delete(int $id): bool
    {
        $eloquent = SolicitudEloquent::find($id);
        
        if (!$eloquent) {
            return false;
        }

        return $eloquent->delete();
    }

    public function findByIdWithUser(int $id): ?array
    {
        $eloquent = SolicitudEloquent::with('user')->find($id);
        
        if (!$eloquent) {
            return null;
        }

        return [
            'solicitud' => $this->toDomain($eloquent),
            'user' => $eloquent->user,
        ];
    }

    public function findPendientesWithUser(): array
    {
        return SolicitudEloquent::with('user')
            ->where(function($query) {
                $query->whereNull('estado')
                      ->orWhere('estado', 'pendiente');
            })
            ->get()
            ->map(function($eloquent) {
                return [
                    'solicitud' => $this->toDomain($eloquent),
                    'user' => $eloquent->user,
                ];
            })
            ->toArray();
    }
}
