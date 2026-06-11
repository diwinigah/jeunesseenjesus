<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectInvestorInterestStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Pledged = 'pledged';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Nouveau',
            self::Contacted => 'Contacté',
            self::Pledged => 'Engagé',
            self::Paid => 'Payé',
            self::Cancelled => 'Annulé',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'gray',
            self::Contacted => 'info',
            self::Pledged => 'primary',
            self::Paid => 'success',
            self::Cancelled => 'danger',
        };
    }
}
