<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use App\Filament\Exports\RegistrationExporter;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CampEdition;
use App\Models\Registration;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    public ?string $activeTab = 'actives';

    /**
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle inscription'),
            ExportAction::make()
                ->label('Exporter Excel')
                ->exporter(RegistrationExporter::class)
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success'),
        ];
    }

    public function getTabs(): array
    {
        $activeEdition = CampEdition::where('is_active', true)->first();

        return [
            'actives' => Tab::make('Édition en cours')
                ->icon('heroicon-o-user-group')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $activeEdition
                        ? $query->where('camp_edition_id', $activeEdition->id)
                        : $query->whereRaw('1 = 0')
                )
                ->badge(
                    $activeEdition
                        ? Registration::where('camp_edition_id', $activeEdition->id)->count()
                        : 0
                ),

            'archives' => Tab::make('Archives')
                ->icon('heroicon-o-archive-box')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $activeEdition
                        ? $query->where('camp_edition_id', '!=', $activeEdition->id)
                        : $query
                )
                ->badge(
                    $activeEdition
                        ? Registration::where('camp_edition_id', '!=', $activeEdition->id)->count()
                        : Registration::count()
                )
                ->badgeColor('gray'),
        ];
    }

    public function getActiveTab(): string | int | null
    {
        return $this->activeTab ?? null;
    }

}
