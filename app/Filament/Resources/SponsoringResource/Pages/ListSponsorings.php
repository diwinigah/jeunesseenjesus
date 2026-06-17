<?php

declare(strict_types=1);

namespace App\Filament\Resources\SponsoringResource\Pages;

use App\Filament\Resources\SponsoringResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSponsorings extends ListRecords
{
    protected static string $resource = SponsoringResource::class;

    protected function getHeaderActions(): array
    {
        // No CreateAction: editions are created elsewhere
        return [];
    }
}
