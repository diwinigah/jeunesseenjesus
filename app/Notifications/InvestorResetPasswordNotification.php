<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvestorResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('investor.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->greeting('Bonjour ' . ($notifiable->name ?? $notifiable->organization_name) . ',')
            ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte investisseur.')
            ->action('Réinitialiser le mot de passe', $url)
            ->line('Ce lien de réinitialisation expirera dans 60 minutes.')
            ->line('Si vous n\'avez pas demandé de réinitialisation, aucune action n\'est requise.');
    }
}
