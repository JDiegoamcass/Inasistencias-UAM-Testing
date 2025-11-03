<?php

namespace App\Domain\States;

use App\Domain\Models\Solicitud;

class PendingState implements RequestStateInterface
{
    public function getStatus(): string
    {
        return 'pendiente';
    }

    public function approve(Solicitud $request, ?string $resolution = null): void
    {
        $request->transitionTo(new ApprovedState());
        if ($resolution) {
            $request->setResolucion($resolution);
        }
    }

    public function reject(Solicitud $request, ?string $resolution = null): void
    {
        $request->transitionTo(new RejectedState());
        if ($resolution) {
            $request->setResolucion($resolution);
        }
    }

    public function canApprove(): bool
    {
        return true;
    }

    public function canReject(): bool
    {
        return true;
    }

    public function isPending(): bool
    {
        return true;
    }

    public function isApproved(): bool
    {
        return false;
    }

    public function isRejected(): bool
    {
        return false;
    }
}
