<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvestorInterestResource\Pages;

use App\Filament\Resources\InvestorInterestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestorInterest extends EditRecord
{
    protected static string $resource = InvestorInterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
