<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\WhatsAppService;
use App\Services\PhoneNumberFormatter;
use Illuminate\Support\Facades\Log;


class WhatsAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $phoneNumber;

    public function __construct($phoneNumber, $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['whatsapp'];
    }

    public function toWhatsApp($notifiable)
    {
        $whatsappService = new WhatsAppService();

        // Normalize phone number to E.164 before sending
        $formatted = PhoneNumberFormatter::format($this->phoneNumber);
        if (!$formatted) {
            Log::error('WhatsAppNotification: invalid phone number', ['phone' => $this->phoneNumber]);
            return false;   
        }

        $whatsappService->sendMessage($formatted, $this->message);
    }
}
