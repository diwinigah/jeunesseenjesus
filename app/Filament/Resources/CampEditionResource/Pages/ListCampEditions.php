<?php

declare(strict_types=1);

namespace App\Filament\Resources\CampEditionResource\Pages;

use App\Filament\Resources\CampEditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCampEditions extends ListRecords
{
    protected static string $resource = CampEditionResource::class;

    /**
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle edition'),
        ];
    }
}
