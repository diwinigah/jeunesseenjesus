<?php
// Test Mail Config
require 'bootstrap/app.php';

$app = app();

try {
    echo "=== TEST 2: Email Direct Send ===\n";
    \Illuminate\Support\Facades\Mail::raw('Test email', function($msg) {
        $msg->to('jeunesseenjesus2026@gmail.com')->subject('Test Laravel Mail');
    });
    echo "✓ Email envoyé sans erreur\n";
} catch (\Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}

echo "\n=== TEST 3: Queue Jobs Count ===\n";
try {
    $count = \Illuminate\Support\Facades\DB::table('jobs')->count();
    echo "Jobs en attente: $count\n";
} catch (\Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== TEST 4: InvestorUser Broker Check ===\n";
try {
    $investor = \App\Models\InvestorUser::first();
    if ($investor) {
        $result = [
            'email' => $investor->email,
            'can_reset' => $investor instanceof \Illuminate\Contracts\Auth\CanResetPassword,
            'has_notifiable' => in_array('Illuminate\Notifications\Notifiable', class_uses_recursive($investor)),
        ];
        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo "✗ Aucun InvestorUser trouvé en base\n";
    }
} catch (\Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== TEST 5: Simulate Password Reset Link ===\n";
try {
    $investor = \App\Models\InvestorUser::first();
    if ($investor) {
        $status = \Illuminate\Support\Facades\Password::broker('investors')
            ->sendResetLink(['email' => $investor->email]);
        echo "Status: $status\n";
    }
} catch (\Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}
