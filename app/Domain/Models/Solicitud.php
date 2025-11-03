<?php

namespace App\Domain\Models;

use App\Domain\States\RequestStateInterface;
use App\Domain\States\PendingState;
use App\Domain\States\ApprovedState;
use App\Domain\States\RejectedState;
use App\Domain\Observers\RequestObserverInterface;
use App\Domain\Observers\RequestObservableInterface;

class Solicitud implements RequestObservableInterface
{
    private ?int $id;
    private int $userId;
    private ?string $comentario;
    private RequestStateInterface $state;
    private ?string $evidencia;
    private ?string $fechaSolicitud;
    private ?string $fechaAusencia;
    private ?string $resolucion;
    private ?string $tipoAusencia;
    
    /**
     * @var RequestObserverInterface[]
     */
    private array $observers = [];

    public function __construct(
        int $userId,
        ?string $comentario = null,
        ?string $estado = null,
        ?string $evidencia = null,
        ?string $fechaSolicitud = null,
        ?string $fechaAusencia = null,
        ?string $resolucion = null,
        ?string $tipoAusencia = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->comentario = $comentario;
        $this->evidencia = $evidencia;
        $this->fechaSolicitud = $fechaSolicitud;
        $this->fechaAusencia = $fechaAusencia;
        $this->resolucion = $resolucion;
        $this->tipoAusencia = $tipoAusencia;
        
        // Initialize state based on estado string or default to pending
        $this->state = $this->createStateFromString($estado);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function getEstado(): ?string
    {
        $status = $this->state->getStatus();
        return $status === 'pendiente' ? null : $status;
    }

    public function getState(): RequestStateInterface
    {
        return $this->state;
    }

    public function getEvidencia(): ?string
    {
        return $this->evidencia;
    }

    public function getFechaSolicitud(): ?string
    {
        return $this->fechaSolicitud;
    }

    public function getFechaAusencia(): ?string
    {
        return $this->fechaAusencia;
    }

    public function getResolucion(): ?string
    {
        return $this->resolucion;
    }

    public function getTipoAusencia(): ?string
    {
        return $this->tipoAusencia;
    }

    public function aprobar(string $resolucion = null): void
    {
        $oldState = $this->state->getStatus();
        $this->state->approve($this, $resolucion);
        $newState = $this->state->getStatus();
        
        // Notify observers
        $this->notifyStateChanged($oldState, $newState, $resolucion);
        
        // Specific notification for approval
        foreach ($this->observers as $observer) {
            $observer->onRequestApproved($this, $resolucion);
        }
    }

    public function rechazar(string $resolucion = null): void
    {
        $oldState = $this->state->getStatus();
        $this->state->reject($this, $resolucion);
        $newState = $this->state->getStatus();
        
        // Notify observers
        $this->notifyStateChanged($oldState, $newState, $resolucion);
        
        // Specific notification for rejection
        foreach ($this->observers as $observer) {
            $observer->onRequestRejected($this, $resolucion);
        }
    }

    public function transitionTo(RequestStateInterface $newState): void
    {
        $oldState = $this->state->getStatus();
        $this->state = $newState;
        $newStateString = $this->state->getStatus();
        
        // Notify observers of state change
        $this->notifyStateChanged($oldState, $newStateString);
    }

    public function setResolucion(?string $resolucion): void
    {
        $this->resolucion = $resolucion;
    }

    public function estaPendiente(): bool
    {
        return $this->state->isPending();
    }

    public function estaAprobada(): bool
    {
        return $this->state->isApproved();
    }

    public function estaRechazada(): bool
    {
        return $this->state->isRejected();
    }

    public function canApprove(): bool
    {
        return $this->state->canApprove();
    }

    public function canReject(): bool
    {
        return $this->state->canReject();
    }

    /**
     * Create state object from string representation
     */
    private function createStateFromString(?string $estado): RequestStateInterface
    {
        if ($estado === null || $estado === 'pendiente') {
            return new PendingState();
        }

        return match($estado) {
            'aprobado' => new ApprovedState(),
            'rechazado' => new RejectedState(),
            default => new PendingState(),
        };
    }

    /**
     * Attach an observer
     */
    public function attach(RequestObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     * Detach an observer
     */
    public function detach(RequestObserverInterface $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            fn($obs) => $obs !== $observer
        );
    }

    /**
     * Notify all observers about a state change
     */
    public function notifyStateChanged(string $oldState, string $newState, ?string $resolution = null): void
    {
        foreach ($this->observers as $observer) {
            try {
                $observer->onRequestStateChanged($this, $oldState, $newState);
            } catch (\Exception $e) {
                // Log error but continue notifying other observers
                error_log("Observer notification failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Get all attached observers
     */
    public function getObservers(): array
    {
        return $this->observers;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'comentario' => $this->comentario,
            'estado' => $this->getEstado(),
            'evidencia' => $this->evidencia,
            'fechaSolicitud' => $this->fechaSolicitud,
            'fechaAusencia' => $this->fechaAusencia,
            'resolucion' => $this->resolucion,
            'tipoAusencia' => $this->tipoAusencia,
        ];
    }
}
