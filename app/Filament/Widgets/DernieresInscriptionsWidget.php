<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Registration;
use App\Services\CampEditionService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class DernieresInscriptionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Dernières inscriptions';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                $campEditionService = app(CampEditionService::class);
                $edition = $campEditionService->getCurrentActiveEdition();

                if ($edition === null) {
                    return Registration::query()->whereRaw('0 = 1');
                }

                return Registration::query()
                    ->byEdition($edition)
                    ->with(['editionSection'])
                    ->orderByDesc('submitted_at')
                    ->limit(5);
            })
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nom complet')
                    ->getStateUsing(fn (Registration $record) => $record->first_name . ' ' . $record->last_name),

                Tables\Columns\TextColumn::make('editionSection.section')
                    ->label('Section')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color()),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Paiement')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        PaymentStatus::Unpaid => 'Non payé',
                        PaymentStatus::Partial => 'Partiel',
                        PaymentStatus::Paid => 'Payé',
                        default => $state->value,
                    })
                    ->color(fn ($state) => match ($state) {
                        PaymentStatus::Unpaid => 'danger',
                        PaymentStatus::Partial => 'warning',
                        PaymentStatus::Paid => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Date soumission')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
