<?php

namespace App\Domain\Notifications;

use App\Domain\Models\Solicitud;

interface NotificationComponentInterface
{
    /**
     * Send/execute the notification
     */
    public function send(Solicitud $request, string $event, ?string $resolution = null): void;

    /**
     * Get the type of notification
     */
    public function getType(): string;

    /**
     * Get notification name/description
     */
    public function getName(): string;
}
