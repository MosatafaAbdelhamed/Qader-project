<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "Testing Reset Password Logic...\n";

// 1. الحصول على التوكن من قاعدة البيانات
$resetRecord = DB::table('password_reset_tokens')
    ->where('email', 'volunteer41@gmail.com')
    ->first();

if (!$resetRecord) {
    echo "No reset record found for volunteer41@gmail.com\n";
    exit;
}

echo "Found reset record for: " . $resetRecord->email . "\n";
echo "Token hash: " . substr($resetRecord->token, 0, 20) . "...\n";
echo "Created at: " . $resetRecord->created_at . "\n";

// 2. إنشاء توكن عشوائي للاختبار
$testToken = 'test_token_123';
$hashedTestToken = Hash::make($testToken);

echo "Test token: " . $testToken . "\n";
echo "Test token hash: " . substr($hashedTestToken, 0, 20) . "...\n";

// 3. اختبار Hash::check
echo "Testing Hash::check...\n";
if (Hash::check($testToken, $hashedTestToken)) {
    echo "✅ Hash::check works correctly\n";
} else {
    echo "❌ Hash::check failed\n";
}

// 4. اختبار مع التوكن الحقيقي (سنحتاج التوكن الأصلي)
echo "\nNote: To test with real token, we need the original token that was sent in the email.\n";
echo "The token in database is hashed, so we can't reverse it.\n";
?>
