<?php

namespace App\Domain\Observers;

use App\Domain\Models\Solicitud;

interface RequestObserverInterface
{
    /**
     * Called when a request is approved
     */
    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void;

    /**
     * Called when a request is rejected
     */
    public function onRequestRejected(Solicitud $request, ?string $resolution = null): void;

    /**
     * Called when a request state changes
     */
    public function onRequestStateChanged(Solicitud $request, string $oldState, string $newState): void;
}
