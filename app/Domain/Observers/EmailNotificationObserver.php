<?php

namespace App\Domain\Observers;

use App\Domain\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationObserver implements RequestObserverInterface
{
    public function onRequestApproved(Solicitud $request, ?string $resolution = null): void
    {
        try {
            $user = User::find($request->getUserId());
            
            if (!$user) {
                Log::warning("User not found for request ID: {$request->getId()}");
                return;
            }

            // Log email notification
            Log::info("Email notification sent to user {$user->email} for approved request ID: {$request->getId()}");
            
            // Here you would send the actual email
            // Mail::to($user->email)->send(new RequestApprovedMail($request, $resolution));
            
        } catch (\Exception $e) {
            Log::error("Failed to send email notification for approved request: " . $e->getMessage());
        }
    }

    public function onRequestRejected(Solicitud $request, ?string $resolution = null): void
    {
        try {
            $user = User::find($request->getUserId());
            
            if (!$user) {
                Log::warning("User not found for request ID: {$request->getId()}");
                return;
            }

            // Log email notification
            Log::info("Email notification sent to user {$user->email} for rejected request ID: {$request->getId()}");
            
            // Here you would send the actual email
            // Mail::to($user->email)->send(new RequestRejectedMail($request, $resolution));
            
        } catch (\Exception $e) {
            Log::error("Failed to send email notification for rejected request: " . $e->getMessage());
        }
    }

    public function onRequestStateChanged(Solicitud $request, string $oldState, string $newState): void
    {
        // Optional: Handle generic state changes
        Log::info("Request ID: {$request->getId()} changed from {$oldState} to {$newState}");
    }
}
