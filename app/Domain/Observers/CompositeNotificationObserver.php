<?php

namespace App\Domain\Observers;

use App\Domain\Models\Solicitud;
use App\Domain\Notifications\NotificationComponentInterface;
use App\Domain\Notifications\CompositeNotification;

/**
 * Observer that uses Composite pattern for notifications
 */
class CompositeNotificationObserver implements RequestObserverInterface
{
    private NotificationComponentInterface $notificationComponent;

    public function __construct(NotificationComponentInterface $notificationComponent)
    {
        $this->notificationComponent = $notificationComponent;
    }

    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void
    {
        $this->notificationComponent->send($request, 'approved', $resolution);
    }

    public function onRequestRejected(Solicitud $request, ?string $resolution = null): void
    {
        $this->notificationComponent->send($request, 'rejected', $resolution);
    }

    public function onRequestStateChanged(Solicitud $request, string $oldState, string $newState): void
    {
        $this->notificationComponent->send($request, 'state_changed');
    }
}
