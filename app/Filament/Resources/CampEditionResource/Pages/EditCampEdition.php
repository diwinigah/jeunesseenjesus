<?php

declare(strict_types=1);

namespace App\Filament\Resources\CampEditionResource\Pages;

use App\Filament\Resources\CampEditionResource;
use App\Models\CampEdition;
use App\Services\CampEditionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCampEdition extends EditRecord
{
    protected static string $resource = CampEditionResource::class;

    /**
     * @param array<string, mixed> $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var CampEdition $record */
        return app(CampEditionService::class)->updateEdition($record, $data);
    }

    /**
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Edition mise a jour';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
