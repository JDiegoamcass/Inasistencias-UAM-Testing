<?php

namespace App\Domain\Services;

use App\Domain\Models\Solicitud;
use App\Domain\Observers\RequestObserverInterface;
use App\Domain\Observers\EmailNotificationObserver;
use App\Domain\Observers\RequestLogObserver;
use App\Domain\Observers\DatabaseAuditObserver;
use App\Domain\Observers\CompositeNotificationObserver;
use App\Domain\Notifications\CompositeNotification;
use App\Domain\Notifications\EmailNotification;
use App\Domain\Notifications\LogNotification;
use App\Domain\Notifications\DatabaseNotification;

class RequestObserverService
{
    /**
     * Attach default observers to a request
     */
    public function attachDefaultObservers(Solicitud $request): void
    {
        // Create composite notification using Composite pattern
        $compositeNotification = $this->createDefaultCompositeNotification();
        
        // Use composite observer that wraps the composite notification
        $compositeObserver = new CompositeNotificationObserver($compositeNotification);
        
        $request->attach($compositeObserver);
        
        // Also attach legacy observers for backward compatibility
        $legacyObservers = $this->getLegacyObservers();
        foreach ($legacyObservers as $observer) {
            $request->attach($observer);
        }
    }

    /**
     * Create default composite notification with all notification types
     */
    private function createDefaultCompositeNotification(): CompositeNotification
    {
        $composite = new CompositeNotification('Default Notifications');
        
        // Add leaf notifications
        $composite->add(new EmailNotification('Request Email Notification'));
        $composite->add(new LogNotification('Request Log Notification'));
        $composite->add(new DatabaseNotification('Request Database Notification'));
        
        return $composite;
    }

    /**
     * Get legacy observers for backward compatibility
     * 
     * @return RequestObserverInterface[]
     */
    private function getLegacyObservers(): array
    {
        return [
            new RequestLogObserver(),
        ];
    }

    /**
     * Create a custom composite notification
     */
    public function createCompositeNotification(string $name): CompositeNotification
    {
        return new CompositeNotification($name);
    }

    /**
     * Attach a custom observer
     */
    public function attachObserver(Solicitud $request, RequestObserverInterface $observer): void
    {
        $request->attach($observer);
    }

    /**
     * Attach a composite notification observer
     */
    public function attachCompositeObserver(Solicitud $request, CompositeNotification $compositeNotification): void
    {
        $observer = new CompositeNotificationObserver($compositeNotification);
        $request->attach($observer);
    }
}
