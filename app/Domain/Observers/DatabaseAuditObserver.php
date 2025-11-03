<?php

namespace App\Domain\Observers;

use App\Domain\Models\Solicitud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseAuditObserver implements RequestObserverInterface
{
    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void
    {
        $this->logAudit($request, 'pendiente', 'aprobado', $resolution);
    }

    public function onRequestRejected(Solicitud $request, ?string $resolution = null): void
    {
        $this->logAudit($request, 'pendiente', 'rechazado', $resolution);
    }

    public function onRequestStateChanged(Solicitud $request, string $oldState, string $newState): void
    {
        try {
            DB::table('request_audit_log')->insert([
                'request_id' => $request->getId(),
                'user_id' => $request->getUserId(),
                'old_state' => $oldState,
                'new_state' => $newState,
                'action' => 'state_changed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log but don't throw - audit logging shouldn't break the main flow
            Log::warning("Failed to write audit log: " . $e->getMessage());
        }
    }

    private function logAudit(Solicitud $request, string $oldState, string $newState, ?string $resolution = null): void
    {
        try {
            DB::table('request_audit_log')->insert([
                'request_id' => $request->getId(),
                'user_id' => $request->getUserId(),
                'old_state' => $oldState,
                'new_state' => $newState,
                'action' => $newState,
                'resolution' => $resolution,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log but don't throw - audit logging shouldn't break the main flow
            Log::warning("Failed to write audit log: " . $e->getMessage());
        }
    }
}
