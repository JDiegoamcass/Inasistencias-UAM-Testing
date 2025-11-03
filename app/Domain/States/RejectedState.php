<?php

namespace App\Domain\States;

use App\Domain\Models\Solicitud;

class RejectedState implements RequestStateInterface
{
    public function getStatus(): string
    {
        return 'rechazado';
    }

    public function approve(Solicitud $request, ?string $resolution = null): void
    {
        throw new \RuntimeException('Cannot approve an already rejected request');
    }

    public function reject(Solicitud $request, ?string $resolution = null): void
    {
        throw new \RuntimeException('Request is already rejected');
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
        return false;
    }

    public function isRejected(): bool
    {
        return true;
    }
}
