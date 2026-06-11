<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\PartnerRequest;
use Filament\Notifications\Actions\Action as FilamentAction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPartnerRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private PartnerRequest $request)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Nouvelle demande de partenariat')
            ->line('Une nouvelle demande de partenariat a été soumise.')
            ->line('**Contact :** ' . $this->request->contact_name)
            ->line('**Organisation :** ' . $this->request->organization_name)
            ->line('**Téléphone :** ' . $this->request->phone)
            ->line('**Email :** ' . ($this->request->email ?? 'Non fourni'))
            ->line('**Type :** ' . ($this->request->type?->label() ?? 'Non spécifié'))
            ->line('**Message :** ' . ($this->request->message ?? 'Aucun'))
            ->action('Voir la demande', url('/admin/partner-requests/' . $this->request->id . '/edit'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Nouvelle demande de partenariat')
            ->body($this->request->organization_name . ' - ' . $this->request->contact_name)
            ->icon('heroicon-o-building-office')
            ->iconColor('info')
            ->actions([
                FilamentAction::make('voir')
                    ->label('Voir la demande')
                    ->url('/admin/partner-requests/' . $this->request->id . '/edit')
                    ->button(),
            ])
            ->getDatabaseMessage();
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
