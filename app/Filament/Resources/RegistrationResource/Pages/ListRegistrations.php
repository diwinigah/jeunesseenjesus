<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use App\Filament\Exports\RegistrationExporter;
use Filament\Resources\Pages\ListRecords;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

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
}
