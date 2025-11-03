<?php

namespace App\Domain\Notifications;

use App\Domain\Models\Solicitud;

/**
 * Composite class that can contain both leaf notifications and other composites
 */
class CompositeNotification implements NotificationComponentInterface
{
    /**
     * @var NotificationComponentInterface[]
     */
    private array $notifications = [];

    private string $name;

    public function __construct(string $name = 'Composite Notification')
    {
        $this->name = $name;
    }

    /**
     * Add a notification component (leaf or composite)
     */
    public function add(NotificationComponentInterface $notification): void
    {
        $this->notifications[] = $notification;
    }

    /**
     * Remove a notification component
     */
    public function remove(NotificationComponentInterface $notification): void
    {
        $this->notifications = array_filter(
            $this->notifications,
            fn($n) => $n !== $notification
        );
    }

    /**
     * Get all child notifications
     */
    public function getChildren(): array
    {
        return $this->notifications;
    }

    /**
     * Send all notifications in the composite
     */
    public function send(Solicitud $request, string $event, ?string $resolution = null): void
    {
        foreach ($this->notifications as $notification) {
            try {
                $notification->send($request, $event, $resolution);
            } catch (\Exception $e) {
                // Log error but continue with other notifications
                error_log("Notification failed in composite: " . $e->getMessage());
            }
        }
    }

    public function getType(): string
    {
        return 'composite';
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get count of all notifications (including nested)
     */
    public function getTotalCount(): int
    {
        $count = 0;
        foreach ($this->notifications as $notification) {
            if ($notification instanceof CompositeNotification) {
                $count += $notification->getTotalCount();
            } else {
                $count++;
            }
        }
        return $count;
    }
}
