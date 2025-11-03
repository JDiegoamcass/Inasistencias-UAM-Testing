<?php

namespace App\Domain\Notifications;

use App\Domain\Models\Solicitud;
use Illuminate\Support\Facades\Log;

/**
 * Leaf component for log notifications
 */
class LogNotification implements NotificationComponentInterface
{
    private string $name;
    private string $logLevel;

    public function __construct(string $name = 'Log Notification', string $logLevel = 'info')
    {
        $this->name = $name;
        $this->logLevel = $logLevel;
    }

    public function send(Solicitud $request, string $event, ?string $resolution = null): void
    {
        $context = [
            'request_id' => $request->getId(),
            'user_id' => $request->getUserId(),
            'event' => $event,
            'resolution' => $resolution,
            'current_state' => $request->getState()->getStatus(),
            'timestamp' => now()->toDateTimeString(),
        ];

        match($this->logLevel) {
            'error' => Log::error("Request notification: {$event}", $context),
            'warning' => Log::warning("Request notification: {$event}", $context),
            'debug' => Log::debug("Request notification: {$event}", $context),
            default => Log::info("Request notification: {$event}", $context),
        };
    }

    public function getType(): string
    {
        return 'log';
    }

    public function getName(): string
    {
        return $this->name;
    }
}
