<?php
// Read .env file manually
$env = [];
$lines = file('.env');
foreach ($lines as $line) {
    $line = trim($line);
    if (!$line || $line[0] === '#') continue;
    [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
    $env[$key] = trim($value, '\'"');
}

$sid = $env['TWILIO_ACCOUNT_SID'] ?? null;
$token = $env['TWILIO_AUTH_TOKEN'] ?? null;
$from = str_replace('whatsapp:', '', $env['TWILIO_WHATSAPP_FROM'] ?? '');
$to = '+923469611585';

echo "=== Twilio WhatsApp Direct Test ===\n\n";
echo "Account SID: " . substr($sid, 0, 5) . "...\n";
echo "From (WhatsApp): whatsapp:$from\n";
echo "To: whatsapp:$to\n";
echo "Message: Test WhatsApp message\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$sid:$token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'From' => "whatsapp:$from",
    'To' => "whatsapp:$to",
    'Body' => 'Test WhatsApp message - ' . date('Y-m-d H:i:s')
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

echo "=== Response ===\n";
echo "HTTP Status: $httpCode\n\n";

if ($httpCode === 201 || $httpCode === 200) {
    echo "✓ SUCCESS! Message sent.\n";
    echo "SID: " . ($data['sid'] ?? 'N/A') . "\n";
    echo "Status: " . ($data['status'] ?? 'N/A') . "\n";
} else {
    echo "❌ ERROR:\n";
    echo "Code: " . ($data['code'] ?? 'Unknown') . "\n";
    echo "Message: " . ($data['message'] ?? 'Unknown') . "\n";
    if (isset($data['more_info'])) {
        echo "Info: " . $data['more_info'] . "\n";
    }
}

echo "\n=== Diagnosis ===\n";
if ($httpCode === 401) {
    echo "⚠ Authentication failed. Check credentials.\n";
} elseif ($httpCode === 400 && isset($data['code'])) {
    if ($data['code'] == 63007) {
        echo "⚠ 'From' number not provisioned for WhatsApp.\n";
        echo "  Ensure +14155238886 is your Twilio WhatsApp Sandbox number.\n";
    } elseif ($data['code'] == 21211) {
        echo "⚠ 'To' number format invalid or not allowed.\n";
        echo "  Verify: $to is E.164 format and recipient joined sandbox.\n";
    }
}
echo "\n";
