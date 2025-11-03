<?php

namespace App\Domain\States;

use App\Domain\Models\Solicitud;

interface RequestStateInterface
{
    /**
     * Get the string representation of the state
     */
    public function getStatus(): string;

    /**
     * Approve the request
     * 
     * @throws \RuntimeException if transition is not allowed
     */
    public function approve(Solicitud $request, ?string $resolution = null): void;

    /**
     * Reject the request
     * 
     * @throws \RuntimeException if transition is not allowed
     */
    public function reject(Solicitud $request, ?string $resolution = null): void;

    /**
     * Check if the request can be approved
     */
    public function canApprove(): bool;

    /**
     * Check if the request can be rejected
     */
    public function canReject(): bool;

    /**
     * Check if request is in pending state
     */
    public function isPending(): bool;

    /**
     * Check if request is approved
     */
    public function isApproved(): bool;

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool;
}
