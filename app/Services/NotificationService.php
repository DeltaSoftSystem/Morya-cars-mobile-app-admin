<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BaseNotification;
use Exception;

class NotificationService
{
    /**
     * Send email notification safely (API-safe)
     */
    public static function sendEmail(string $email = null, string $subject, array $data = []): void
    {
        if (empty($email)) {
            return;
        }

        try {
            Mail::to($email)->send(
                new BaseNotification($subject, $data)
            );
        } catch (Exception $e) {
            Log::error('Email notification failed', [
                'email'   => $email,
                'subject' => $subject,
                'error'   => $e->getMessage()
            ]);
        }
    }
}
