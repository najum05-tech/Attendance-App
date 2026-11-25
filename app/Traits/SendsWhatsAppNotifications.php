<?php

namespace App\Traits;

use App\Notifications\WhatsAppNotification;
use App\Services\PhoneNumberFormatter;
use Illuminate\Support\Facades\Log;

trait SendsWhatsAppNotifications
{
    /**
     * Send a WhatsApp notification to a user
     *
     * @param string $phoneNumber User's phone number in international format (e.g., 1234567890)
     * @param string $message The message to send
     * @return void
     */
    protected function sendWhatsAppNotification($phoneNumber, $message)
    {
        try {
            // Ensure phone number is normalized to E.164 (+123...)
            $formattedNumber = PhoneNumberFormatter::format($phoneNumber);

            if (!$formattedNumber) {
                throw new \Exception('Invalid phone number for WhatsApp notification');
            }

            // Send the notification (notification will call the service)
            $notification = new WhatsAppNotification($formattedNumber, $message);
            $notification->toWhatsApp($formattedNumber);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number to international format
     *
     * @param string $phoneNumber
     * @return string
     */
    // Phone number formatting is handled centrally by App\Services\PhoneNumberFormatter
}
