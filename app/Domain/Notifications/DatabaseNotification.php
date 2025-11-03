<?php

namespace App\Domain\Notifications;

use App\Domain\Models\Solicitud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Leaf component for database notifications (audit trail)
 */
class DatabaseNotification implements NotificationComponentInterface
{
    private string $name;

    public function __construct(string $name = 'Database Notification')
    {
        $this->name = $name;
    }

    public function send(Solicitud $request, string $event, ?string $resolution = null): void
    {
        try {
            DB::table('request_notifications')->insert([
                'request_id' => $request->getId(),
                'user_id' => $request->getUserId(),
                'event_type' => $event,
                'resolution' => $resolution,
                'state' => $request->getState()->getStatus(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log but don't throw - notification shouldn't break the main flow
            Log::warning("Failed to save database notification: " . $e->getMessage());
        }
    }

    public function getType(): string
    {
        return 'database';
    }

    public function getName(): string
    {
        return $this->name;
    }
}
