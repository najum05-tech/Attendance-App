<?php
require 'vendor/autoload.php';

use Twilio\Rest\Client;

// Load .env using Laravel's helper
require 'bootstrap/app.php';
$sid = env('TWILIO_ACCOUNT_SID');
$token = env('TWILIO_AUTH_TOKEN');
$from = env('TWILIO_WHATSAPP_FROM');

echo "=== Twilio WhatsApp Sandbox Diagnostics ===\n\n";

echo "[1] Checking credentials...\n";
if (!$sid || !$token) {
    echo "❌ ERROR: TWILIO_ACCOUNT_SID or TWILIO_AUTH_TOKEN not set\n";
    exit(1);
}
echo "✓ SID: " . substr($sid, 0, 5) . "...\n";
echo "✓ Token: " . substr($token, 0, 5) . "...\n";
echo "✓ From: $from\n\n";

echo "[2] Testing Twilio SDK connection...\n";
try {
    $client = new Client($sid, $token);
    $account = $client->api->accounts($sid)->fetch();
    echo "✓ Connected to Twilio Account\n";
    echo "✓ Account Type: " . ($account->type ?? 'Unknown') . "\n";
    echo "✓ Account Status: " . ($account->status ?? 'Unknown') . "\n\n";
} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "[3] Checking WhatsApp Sandbox configuration...\n";
echo "⚠ Skipping SDK service check (manual verification recommended)\n";
echo "  Check Twilio Console > Messaging > Try it Out > WhatsApp for sandbox code\n\n";

echo "[4] Testing direct WhatsApp API call (HTTP)...\n";
$to = '+923469611585';  // Your phone number in E.164
$message = 'Test from diagnostics - ' . date('Y-m-d H:i:s');

$from_clean = str_replace('whatsapp:', '', $from);

$payload = json_encode([
    'From' => 'whatsapp:' . $from_clean,
    'To' => 'whatsapp:' . $to,
    'Body' => $message
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/Messages.json');
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'From' => 'whatsapp:' . $from_clean,
    'To' => 'whatsapp:' . $to,
    'Body' => $message
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$decoded = json_decode($response, true);

echo "HTTP Status: $httpCode\n";
if ($httpCode === 201 || $httpCode === 200) {
    echo "✓ Message queued successfully!\n";
    echo "  Message SID: " . ($decoded['sid'] ?? 'N/A') . "\n";
} else {
    echo "❌ API Error:\n";
    echo "  Code: " . ($decoded['code'] ?? 'Unknown') . "\n";
    echo "  Message: " . ($decoded['message'] ?? 'Unknown') . "\n";
    if (isset($decoded['more_info'])) {
        echo "  Details: " . $decoded['more_info'] . "\n";
    }
}

echo "\n[5] Troubleshooting checklist:\n";
echo "□ Verify TWILIO_ACCOUNT_SID and TWILIO_AUTH_TOKEN are correct\n";
echo "□ Ensure your WhatsApp sandbox is enabled in Twilio Console\n";
echo "□ Check that recipient ($to) has joined the sandbox\n";
echo "□ Verify the 'From' phone (+14155238886) matches your Twilio sandbox\n";
echo "□ Check Twilio Console > Messaging > WhatsApp Sandbox for join code\n";
echo "\nNote: If join code is wrong, check your Twilio Console for the exact code to send.\n";
echo "Usually it's: 'join <code>' sent to +1 415 523 8886\n";
