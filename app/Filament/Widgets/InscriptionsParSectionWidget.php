<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\EditionSection;
use App\Models\Registration;
use App\Services\CampEditionService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class InscriptionsParSectionWidget extends BaseWidget
{
    protected static ?string $heading = 'Inscrits par section';

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                $campEditionService = app(CampEditionService::class);
                $edition = $campEditionService->getCurrentActiveEdition();

                if ($edition === null) {
                    return EditionSection::query()->whereRaw('0 = 1');
                }

                return EditionSection::query()
                    ->where('camp_edition_id', $edition->getKey())
                    ->with(['registrations' => function ($query) {
                        $query->select('edition_section_id', 'paid_amount', 'remaining_amount');
                    }])
                    ->orderByDesc(
                        Registration::query()
                            ->selectRaw('count(*)')
                            ->whereColumn('edition_section_id', '=', 'edition_sections.id')
                    );
            })
            ->columns([
                Tables\Columns\TextColumn::make('section')
                    ->label('Section')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),

                Tables\Columns\TextColumn::make('registrations_count')
                    ->label('Nombre d\'inscrits')
                    ->getStateUsing(fn (EditionSection $record) => $record->registrations->count()),

                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Montant collecté')
                    ->getStateUsing(function (EditionSection $record) {
                        $totalPaid = (float) $record->registrations->sum('paid_amount');
                        return number_format($totalPaid, 0, ',', ' ') . ' F CFA';
                    }),

                Tables\Columns\TextColumn::make('total_remaining')
                    ->label('Montant restant')
                    ->getStateUsing(function (EditionSection $record) {
                        $totalRemaining = (float) $record->registrations->sum('remaining_amount');
                        return number_format($totalRemaining, 0, ',', ' ') . ' F CFA';
                    }),
            ])
            ->paginated(false);
    }
}
