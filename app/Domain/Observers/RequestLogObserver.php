<?php

namespace App\Domain\Observers;

use App\Domain\Models\Solicitud;
use Illuminate\Support\Facades\Log;

class RequestLogObserver implements RequestObserverInterface
{
    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void
    {
        Log::info("Request approved", [
            'request_id' => $request->getId(),
            'user_id' => $request->getUserId(),
            'resolution' => $resolution,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    public function onRequestRejected(Solicitud $request, ?string $resolution = null): void
    {
        Log::info("Request rejected", [
            'request_id' => $request->getId(),
            'user_id' => $request->getUserId(),
            'resolution' => $resolution,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    public function onRequestStateChanged(Solicitud $request, string $oldState, string $newState): void
    {
        Log::info("Request state changed", [
            'request_id' => $request->getId(),
            'user_id' => $request->getUserId(),
            'old_state' => $oldState,
            'new_state' => $newState,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
