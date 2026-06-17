<?php

declare(strict_types=1);

namespace App\Filament\Resources\SponsoringResource\Pages;

use App\Filament\Resources\SponsoringResource;
use App\Models\CampEdition;
use App\Services\CampEditionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSponsoring extends EditRecord
{
    protected static string $resource = SponsoringResource::class;

    /**
     * @param array<string, mixed> $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var CampEdition $record */
        // Liste blanche des champs sponsoring uniquement
        $sponsoringFields = [
            'show_sponsoring_page',
            'sponsoring_theme',
            'sponsoring_intro',
            'sponsoring_verse',
            'budget_total',
            'budget_collected',
            'participants_target',
            'participants_sponsored',
            'bourse_pleine_amount',
            'bourse_adulte_amount',
            'bourse_etudiant_amount',
            'bourse_lycee_amount',
            'bourse_enfant_amount',
            'payment_flooz',
            'payment_mixx',
            'payment_iban',
            'payment_paypal',
            'payment_account_name',
            'payment_account_number',
            'sponsoring_contact_phone',
            'sponsoring_contact_email',
            'nature_contributions',
            'budget_entries', 'budget_expenses',
            'participants_adultes', 'participants_etudiants',
            'participants_lycee', 'participants_enfants',
            'participants_geo',
        ];

        // Utiliser le service si des transformations supplémentaires existent
        if (app()->bound(CampEditionService::class)) {
            // Déléguer à la méthode de service si elle attend tout le tableau
            return app(CampEditionService::class)->updateEdition($record, $data);
        }

        // Sinon, mise à jour manuelle en ne touchant qu'aux champs sponsoring
        // Ne jamais écraser is_active via ce formulaire
        $filtered = collect($data)->only($sponsoringFields)->toArray();
        $record->fill($filtered);
        $record->save();

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Sponsoring mis a jour';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
