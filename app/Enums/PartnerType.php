<?php

declare(strict_types=1);

namespace App\Enums;

enum PartnerType: string
{
    case Church = 'church';
    case Company = 'company';
    case Association = 'association';
    case Individual = 'individual';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Church => 'Église',
            self::Company => 'Entreprise',
            self::Association => 'Association',
            self::Individual => 'Individuel',
            self::Other => 'Autre',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Church => 'info',
            self::Company => 'success',
            self::Association => 'primary',
            self::Individual => 'warning',
            self::Other => 'gray',
        };
    }
}
