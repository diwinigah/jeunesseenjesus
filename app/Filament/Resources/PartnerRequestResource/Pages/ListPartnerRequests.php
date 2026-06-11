<?php

declare(strict_types=1);

namespace App\Filament\Resources\PartnerRequestResource\Pages;

use App\Filament\Resources\PartnerRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListPartnerRequests extends ListRecords
{
    protected static string $resource = PartnerRequestResource::class;
}
