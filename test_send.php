<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// Get config
$accountSid = config('whatsapp.twilio_account_sid');
$authToken = config('whatsapp.twilio_auth_token');
$from = config('whatsapp.twilio_whatsapp_from');

echo "=== WhatsApp Send Test ===\n";
echo "Account SID: " . (substr($accountSid, 0, 10) ?? 'MISSING') . "...\n";
echo "Auth Token: " . (substr($authToken, 0, 10) ?? 'MISSING') . "...\n";
echo "From: " . ($from ?? 'MISSING') . "\n\n";

if (!$accountSid || !$authToken || !$from) {
    echo "❌ Missing credentials in config!\n";
    echo "Tried to read from config('whatsapp.*')\n";
    exit(1);
}

// Test number - CHANGE THIS TO YOUR ACTUAL PHONE NUMBER
$testNumber = '+923001234567';
$testMessage = "✅ Test message from WhatsApp integration\n\nIf you see this, WhatsApp sending is working!";

echo "Sending to: $testNumber\n";
echo "Message: " . substr($testMessage, 0, 50) . "...\n\n";

// Ensure proper whatsapp: format
$to = strpos($testNumber, 'whatsapp:') === 0 ? $testNumber : 'whatsapp:' . $testNumber;

$url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";

$response = Http::withBasicAuth($accountSid, $authToken)
    ->asForm()
    ->post($url, [
        'From' => $from,
        'To' => $to,
        'Body' => $testMessage,
    ]);

if ($response->successful()) {
    echo "✅ SUCCESS! Message sent with SID: " . $response->json('sid') . "\n";
} else {
    echo "❌ FAILED!\n";
    echo "Status: " . $response->status() . "\n";
    echo "Response: " . $response->body() . "\n";
}
?>

