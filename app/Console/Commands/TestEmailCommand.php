<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {--to=test@example.com}';

    protected $description = 'Test la configuration SMTP en envoyant un email simple';

    public function handle(): int
    {
        $email = $this->option('to');

        $this->info('Envoi en cours...');

        try {
            Mail::raw('Configuration SMTP fonctionnelle.', function ($message) use ($email): void {
                $message->to($email)
                    ->subject('Test email Jeunesse en Jésus');
            });

            $this->info("Email envoyé avec succès à {$email}");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
