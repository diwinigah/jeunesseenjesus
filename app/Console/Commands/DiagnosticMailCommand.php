<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnosticMailCommand extends Command
{
    protected $signature = 'diagnostic:mail';

    protected $description = 'Diagnostic complet pour mail';

    public function handle()
    {
        $this->info('=== TEST 2: Email Direct Send ===');
        try {
            \Illuminate\Support\Facades\Mail::raw('Test email', function($msg) {
                $msg->to('jeunesseenjesus2026@gmail.com')->subject('Test Laravel Mail');
            });
            $this->info('✓ Email envoyé sans erreur');
        } catch (\Exception $e) {
            $this->error('✗ Exception: ' . $e->getMessage());
            $this->error('Code: ' . $e->getCode());
        }

        $this->info("\n=== TEST 3: Queue Jobs Count ===");
        try {
            $count = \Illuminate\Support\Facades\DB::table('jobs')->count();
            $this->info("Jobs en attente: $count");
        } catch (\Exception $e) {
            $this->error('✗ Exception: ' . $e->getMessage());
        }

        $this->info("\n=== TEST 4: InvestorUser Broker Check ===");
        try {
            $investor = \App\Models\InvestorUser::first();
            if ($investor) {
                $result = [
                    'email' => $investor->email,
                    'can_reset' => $investor instanceof \Illuminate\Contracts\Auth\CanResetPassword,
                    'has_notifiable' => in_array('Illuminate\Notifications\Notifiable', class_uses_recursive($investor)),
                ];
                $this->info(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            } else {
                $this->error('✗ Aucun InvestorUser trouvé en base');
            }
        } catch (\Exception $e) {
            $this->error('✗ Exception: ' . $e->getMessage());
        }

        $this->info("\n=== TEST 5: Simulate Password Reset Link ===");
        try {
            $investor = \App\Models\InvestorUser::first();
            if ($investor) {
                $status = \Illuminate\Support\Facades\Password::broker('investors')
                    ->sendResetLink(['email' => $investor->email]);
                $this->info("Status: $status");
            }
        } catch (\Exception $e) {
            $this->error('✗ Exception: ' . $e->getMessage());
        }
    }
}
