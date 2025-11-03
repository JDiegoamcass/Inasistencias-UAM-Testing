<?php

namespace App\Domain\Observers;

interface RequestObservableInterface
{
    /**
     * Attach an observer
     */
    public function attach(RequestObserverInterface $observer): void;

    /**
     * Detach an observer
     */
    public function detach(RequestObserverInterface $observer): void;

    /**
     * Notify all observers about a state change
     */
    public function notifyStateChanged(string $oldState, string $newState, ?string $resolution = null): void;
}
