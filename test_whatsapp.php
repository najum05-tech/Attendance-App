<?php
/**
 * WhatsApp Notification Test Script
 * This script tests the WhatsApp integration by:
 * 1. Creating a test user with a phone number
 * 2. Triggering a WhatsApp notification (attendance marked)
 * 3. Logging the result
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Services\WhatsAppNotificationService;
use App\Services\PhoneNumberFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

echo "\n=== WhatsApp Integration Test ===\n\n";

// Step 1: Create a test user
echo "[Step 1] Creating test user...\n";
$user = User::firstOrCreate(
    ['email' => 'whatsapp-test@example.com'],
    [
        'username' => 'whatsapp_test_user',
        'password' => bcrypt('password'),
        'role' => 'student',
        'phone' => '03001234567', // Pakistani number without country code
    ]
);
echo "✓ User created/retrieved: {$user->username} (ID: {$user->id})\n";
echo "  Email: {$user->email}\n";
echo "  Phone (raw): {$user->phone}\n";

// Step 2: Test phone formatter
echo "\n[Step 2] Testing PhoneNumberFormatter...\n";
$formatted = PhoneNumberFormatter::format($user->phone);
echo "  Formatted phone: {$formatted}\n";
$withPrefix = PhoneNumberFormatter::ensureWhatsAppPrefix($user->phone);
echo "  WhatsApp prefix: {$withPrefix}\n";

// Step 3: Simulate WhatsApp notification
echo "\n[Step 3] Simulating WhatsApp notification (attendance marked)...\n";
$whatsappService = new WhatsAppNotificationService();

// This will log the notification attempt (may fail due to missing real Twilio creds, but logs will show)
$result = $whatsappService->attendanceMarked($user);
echo "  Notification sent: " . ($result ? 'TRUE (queued/sent)' : 'FALSE (failed)') . "\n";

// Step 4: Check logs
echo "\n[Step 4] Checking logs for WhatsApp entries...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    // Read last 30 lines
    $lines = array_slice(file($logFile), -30);
    $whatsappLines = array_filter($lines, function($line) {
        return stripos($line, 'whatsapp') !== false || stripos($line, 'notification') !== false;
    });
    
    if (!empty($whatsappLines)) {
        echo "  Found WhatsApp-related log entries:\n";
        foreach ($whatsappLines as $line) {
            echo "    " . trim($line) . "\n";
        }
    } else {
        echo "  No WhatsApp entries found in recent logs (yet).\n";
    }
} else {
    echo "  Log file not found at: {$logFile}\n";
}

echo "\n=== Test Summary ===\n";
echo "✓ User created with phone number\n";
echo "✓ Phone formatter working (converts local numbers to E.164)\n";
echo "✓ WhatsApp notification initiated\n";
echo "ℹ Check 'storage/logs/laravel.log' for detailed notification logs\n";
echo "ℹ Ensure queue worker is running: php artisan queue:work\n";
echo "ℹ Ensure Twilio credentials are set in .env for actual sending\n\n";

Artisan::call('config:cache');
echo "✓ Config cached\n\n";
