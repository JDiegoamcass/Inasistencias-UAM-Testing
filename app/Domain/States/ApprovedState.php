<?php

namespace App\Domain\States;

use App\Domain\Models\Solicitud;

class ApprovedState implements RequestStateInterface
{
    public function getStatus(): string
    {
        return 'aprobado';
    }

    public function approve(Solicitud $request, ?string $resolution = null): void
    {
        throw new \RuntimeException('Request is already approved');
    }

    public function reject(Solicitud $request, ?string $resolution = null): void
    {
        throw new \RuntimeException('Cannot reject an already approved request');
    }

    public function canApprove(): bool
    {
        return false;
    }

    public function canReject(): bool
    {
        return false;
    }

    public function isPending(): bool
    {
        return false;
    }

    public function isApproved(): bool
    {
        return true;
    }

    public function isRejected(): bool
    {
        return false;
    }
}
