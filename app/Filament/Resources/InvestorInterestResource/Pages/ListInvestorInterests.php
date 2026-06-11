<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvestorInterestResource\Pages;

use App\Filament\Resources\InvestorInterestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvestorInterests extends ListRecords
{
    protected static string $resource = InvestorInterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
