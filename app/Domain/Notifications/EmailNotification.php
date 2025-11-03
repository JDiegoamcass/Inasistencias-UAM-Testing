<?php

namespace App\Domain\Notifications;

use App\Domain\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Leaf component for email notifications
 */
class EmailNotification implements NotificationComponentInterface
{
    private string $name;

    public function __construct(string $name = 'Email Notification')
    {
        $this->name = $name;
    }

    public function send(Solicitud $request, string $event, ?string $resolution = null): void
    {
        try {
            $user = User::find($request->getUserId());
            
            if (!$user) {
                Log::warning("User not found for request ID: {$request->getId()}");
                return;
            }

            $subject = $this->getEmailSubject($event);
            $body = $this->getEmailBody($request, $event, $resolution);

            Log::info("Email notification sent", [
                'to' => $user->email,
                'subject' => $subject,
                'request_id' => $request->getId(),
                'event' => $event,
            ]);

            // Here you would send the actual email
            // Mail::to($user->email)->send(new RequestNotificationMail($subject, $body));
            
        } catch (\Exception $e) {
            Log::error("Failed to send email notification: " . $e->getMessage());
            throw $e;
        }
    }

    public function getType(): string
    {
        return 'email';
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function getEmailSubject(string $event): string
    {
        return match($event) {
            'approved' => 'Your absence request has been approved',
            'rejected' => 'Your absence request has been rejected',
            'state_changed' => 'Your absence request status has changed',
            default => 'Absence request update',
        };
    }

    private function getEmailBody(Solicitud $request, string $event, ?string $resolution): string
    {
        $baseMessage = "Your absence request #{$request->getId()} ";
        
        return match($event) {
            'approved' => $baseMessage . "has been approved. " . ($resolution ? "Resolution: {$resolution}" : ""),
            'rejected' => $baseMessage . "has been rejected. " . ($resolution ? "Reason: {$resolution}" : ""),
            default => $baseMessage . "status has been updated.",
        };
    }
}
