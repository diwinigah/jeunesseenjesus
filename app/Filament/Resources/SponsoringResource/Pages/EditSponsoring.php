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
            'sponsoring_salutation',
            'sponsoring_intro',
            'sponsoring_verse',
            'budget_total',
            'budget_collected',
            'participants_target',
            'participants_sponsored',
            'bourse_pleine_amount',
            'bourse_pleine_label', 'bourse_pleine_desc',
            'bourse_partielle_label', 'bourse_partielle_desc',
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

        // Préparer le payload des champs sponsoring uniquement
        $payload = collect($data)->only($sponsoringFields)->toArray();

        // Forcer 0 si null sur les champs numériques NOT NULL
        $numericNotNull = [
            'bourse_pleine_amount', 'bourse_adulte_amount',
            'bourse_etudiant_amount', 'bourse_lycee_amount',
            'bourse_enfant_amount', 'budget_total', 'budget_collected',
            'participants_target', 'participants_sponsored',
            'participants_adultes', 'participants_etudiants',
            'participants_lycee', 'participants_enfants',
        ];

        foreach ($numericNotNull as $field) {
            if (array_key_exists($field, $payload) && is_null($payload[$field])) {
                $payload[$field] = 0;
            }
        }

        // Utiliser forceFill pour bypasser les protections de mass-assignment
        // (le modèle `CampEdition` n'avait pas `sponsoring_salutation` dans $fillable)
        $record->forceFill($payload)->save();

        // Si un service est lié, déléguer les mises à jour restantes
        if (app()->bound(CampEditionService::class)) {
            $other = collect($data)->except($sponsoringFields)->toArray();
            if (!empty($other)) {
                return app(CampEditionService::class)->updateEdition($record, $other);
            }
            return $record->refresh();
        }

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
