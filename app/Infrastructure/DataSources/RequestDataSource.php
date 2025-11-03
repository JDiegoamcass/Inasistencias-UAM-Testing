<?php

namespace App\Infrastructure\DataSources;

use App\Domain\Models\Solicitud;
use App\Domain\Repositories\RequestRepositoryInterface;
use App\Domain\Services\RequestObserverService;
use App\Models\Solicitud as SolicitudEloquent;

class RequestDataSource implements RequestRepositoryInterface
{
    public function __construct(
        private RequestObserverService $observerService
    ) {}

    private function toDomain(SolicitudEloquent $eloquent): Solicitud
    {
        $solicitud = new Solicitud(
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

        // Attach default observers
        $this->observerService->attachDefaultObservers($solicitud);

        return $solicitud;
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
        // Ensure observers are attached before saving
        if (empty($solicitud->getObservers())) {
            $this->observerService->attachDefaultObservers($solicitud);
        }

        $eloquent = SolicitudEloquent::create($this->toEloquent($solicitud));
        
        // Reload with observers attached
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
}
