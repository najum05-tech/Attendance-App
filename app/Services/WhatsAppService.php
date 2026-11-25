<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $this->from = config('services.twilio.whatsapp_number');
        
        // Validate credentials before creating client
        if (empty($sid) || empty($token)) {
            Log::warning('Twilio credentials not configured. WhatsApp notifications disabled.');
            $this->client = null;
            return;
        }
        
        try {
            $this->client = new Client($sid, $token);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Twilio Client: ' . $e->getMessage());
            $this->client = null;
        }
    }

    public function sendMessage($to, $message)
    {
        // Check if client is initialized
        if (is_null($this->client)) {
            Log::error('Twilio Client not initialized. Cannot send WhatsApp message.');
            return false;
        }

        try {
            $this->client->messages->create(
                "whatsapp:{$to}",
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );
            Log::info('WhatsApp message sent successfully to: ' . $to);
            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp message failed: ' . $e->getMessage());
            return false;
        }
    }
}
