<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\PhoneNumberFormatter;

class WhatsAppNotificationService
{
    private $provider;
    private $config;

    public function __construct()
    {
        $this->provider = config('services.whatsapp.provider', 'twilio'); // twilio, whatsapp_business, generic
        $this->config = config('services.whatsapp');
    }

    /**
     * Send WhatsApp notification
     */
    public function send($to, $message)
    {
        try {
            // Format phone number (remove +, spaces, etc.)
            $phoneNumber = $this->formatPhoneNumber($to);
            
            if (!$phoneNumber) {
                Log::warning('WhatsApp: Invalid phone number', ['to' => $to]);
                return false;
            }

            // Log for development/testing
            Log::info('WhatsApp Notification', [
                'provider' => $this->provider,
                'to' => $phoneNumber,
                'message' => $message
            ]);

            // Send based on provider
            switch ($this->provider) {
                case 'twilio':
                    return $this->sendViaTwilio($phoneNumber, $message);
                case 'whatsapp_business':
                    return $this->sendViaWhatsAppBusiness($phoneNumber, $message);
                case 'generic':
                    return $this->sendViaGenericAPI($phoneNumber, $message);
                default:
                    Log::error('WhatsApp: Unknown provider', ['provider' => $this->provider]);
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send via Twilio WhatsApp API
     */
    private function sendViaTwilio($to, $message)
    {
        $accountSid = $this->config['twilio_account_sid'] ?? env('TWILIO_ACCOUNT_SID');
        $authToken = $this->config['twilio_auth_token'] ?? env('TWILIO_AUTH_TOKEN');
        $from = $this->config['twilio_whatsapp_from'] ?? env('TWILIO_WHATSAPP_FROM'); // Format: whatsapp:+14155238886

        if (!$accountSid || !$authToken || !$from) {
            Log::error('WhatsApp Twilio: Missing credentials');
            return false;
        }

        // Ensure proper whatsapp: prefix for both numbers
        $to = $this->ensureWhatsAppFormat($to);
        $from = $this->ensureWhatsAppFormat($from);

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";

        // Prepare payload for logging/debugging
        $payload = [
            'From' => $from,
            'To' => $to,
            'Body' => $message,
        ];

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post($url, $payload);

        if ($response->successful()) {
            Log::info('WhatsApp Twilio: Message sent successfully', [
                'sid' => $response->json('sid')
            ]);
            return true;
        }

        // Log detailed failure information
        Log::error('WhatsApp Twilio: Failed to send', [
            'status' => $response->status(),
            'response' => $response->body(),
            'payload' => $payload,
        ]);

        // Specific guidance for authentication errors
        if ($response->status() === 401) {
            Log::error('WhatsApp Twilio: Authentication failed. Check TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN, and TWILIO_WHATSAPP_FROM in your .env. If using Twilio sandbox, ensure recipient has joined the sandbox.');
            return false;
        }

        // Fallback: attempt using Twilio SDK if available (sometimes environment permits SDK while HTTP fails)
        try {
            if (class_exists('\Twilio\Rest\Client')) {
                $client = new \Twilio\Rest\Client($accountSid, $authToken);
                $result = $client->messages->create($to, [
                    'from' => $from,
                    'body' => $message,
                ]);
                Log::info('WhatsApp Twilio (SDK) attempt result', ['sid' => $result->sid ?? null]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Twilio SDK fallback failed: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Send via WhatsApp Business API (Meta)
     */
    private function sendViaWhatsAppBusiness($to, $message)
    {
        $accessToken = $this->config['whatsapp_business_token'] ?? env('WHATSAPP_BUSINESS_TOKEN');
        $phoneNumberId = $this->config['whatsapp_business_phone_id'] ?? env('WHATSAPP_BUSINESS_PHONE_ID');
        $businessAccountId = $this->config['whatsapp_business_account_id'] ?? env('WHATSAPP_BUSINESS_ACCOUNT_ID');

        if (!$accessToken || !$phoneNumberId) {
            Log::error('WhatsApp Business: Missing credentials');
            return false;
        }

        // Format phone number (remove +, ensure country code)
        $to = preg_replace('/[^0-9]/', '', $to);
        if (!str_starts_with($to, '1') && !str_starts_with($to, '91')) {
            // Add default country code if not present (adjust as needed)
            $to = '1' . $to; // Default to US, change as needed
        }

        $url = "https://graph.facebook.com/v18.0/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

        if ($response->successful()) {
            Log::info('WhatsApp Business: Message sent successfully', [
                'message_id' => $response->json('messages.0.id')
            ]);
            return true;
        } else {
            Log::error('WhatsApp Business: Failed to send', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
        }
    }

    /**
     * Send via Generic HTTP API
     */
    private function sendViaGenericAPI($to, $message)
    {
        $apiUrl = $this->config['api_url'] ?? env('WHATSAPP_API_URL');
        $apiKey = $this->config['api_key'] ?? env('WHATSAPP_API_KEY');

        if (!$apiUrl || !$apiKey) {
            Log::error('WhatsApp Generic: Missing API URL or Key');
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'to' => $to,
            'message' => $message,
        ]);

        if ($response->successful()) {
            Log::info('WhatsApp Generic: Message sent successfully');
            return true;
        } else {
            Log::error('WhatsApp Generic: Failed to send', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
        }
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Use centralized formatter that returns E.164 (e.g., +1234567890) or null
        return PhoneNumberFormatter::format($phone);
    }

    /**
     * Ensure phone number is in WhatsApp format (whatsapp:+1234567890)
     */
    private function ensureWhatsAppFormat($phone)
    {
        return PhoneNumberFormatter::ensureWhatsAppPrefix($phone);
    }

    /**
     * Get user phone number with fallback
     */
    private function getUserPhone($user)
    {
        // Check if user has phone field
        if (isset($user->phone) && !empty($user->phone)) {
            // Normalize before returning
            return PhoneNumberFormatter::format($user->phone);
        }

        // Return null if no phone - WhatsApp requires phone numbers
        return null;
    }

    /**
     * Send attendance marked notification
     */
    public function attendanceMarked($user)
    {
        $phone = $this->getUserPhone($user);
        if (!$phone) {
            Log::info('WhatsApp: Skipping notification - no phone number for user', ['user_id' => $user->id]);
            return false;
        }

        $message = "✅ Attendance Marked Successfully!\n\n";
        $message .= "Hello {$user->username},\n";
        $message .= "Your attendance has been marked for today (" . now()->format('M d, Y') . ").\n";
        $message .= "Thank you!";

        return $this->send($phone, $message);
    }

    /**
     * Send leave request notification
     */
    public function leaveRequested($user, $leave)
    {
        $phone = $this->getUserPhone($user);
        if (!$phone) {
            Log::info('WhatsApp: Skipping notification - no phone number for user', ['user_id' => $user->id]);
            return false;
        }

        $message = "📅 Leave Request Submitted\n\n";
        $message .= "Hello {$user->username},\n";
        $message .= "Your leave request from " . \Carbon\Carbon::parse($leave->from_date ?? $leave->start_date)->format('M d') . " to " . \Carbon\Carbon::parse($leave->to_date ?? $leave->end_date)->format('M d') . " has been submitted.\n";
        $message .= "Status: Pending\n";
        $message .= "We'll notify you once it's reviewed.";

        return $this->send($phone, $message);
    }

    /**
     * Send task assigned notification
     */
    public function taskAssigned($user, $task)
    {
        $phone = $this->getUserPhone($user);
        if (!$phone) {
            Log::info('WhatsApp: Skipping notification - no phone number for user', ['user_id' => $user->id]);
            return false;
        }

        $message = "📋 New Task Assigned\n\n";
        $message .= "Hello {$user->username},\n";
        $message .= "A new task has been assigned to you:\n";
        $message .= "Title: {$task->title}\n";
        $message .= "Please check your dashboard for details.";

        return $this->send($phone, $message);
    }

    /**
     * Send task approved notification
     */
    public function taskApproved($user, $task)
    {
        $phone = $this->getUserPhone($user);
        if (!$phone) {
            Log::info('WhatsApp: Skipping notification - no phone number for user', ['user_id' => $user->id]);
            return false;
        }

        $message = "✅ Task Approved!\n\n";
        $message .= "Hello {$user->username},\n";
        $message .= "Your task '{$task->title}' has been approved.\n";
        if ($task->admin_feedback) {
            $message .= "Feedback: {$task->admin_feedback}";
        }

        return $this->send($phone, $message);
    }

    /**
     * Send task rejected notification
     */
    public function taskRejected($user, $task)
    {
        $phone = $this->getUserPhone($user);
        if (!$phone) {
            Log::info('WhatsApp: Skipping notification - no phone number for user', ['user_id' => $user->id]);
            return false;
        }

        $message = "❌ Task Needs Revision\n\n";
        $message .= "Hello {$user->username},\n";
        $message .= "Your task '{$task->title}' has been rejected.\n";
        if ($task->admin_feedback) {
            $message .= "Feedback: {$task->admin_feedback}\n";
        }
        $message .= "Please review and resubmit.";

        return $this->send($phone, $message);
    }
}

