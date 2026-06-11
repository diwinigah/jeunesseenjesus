<?php

declare(strict_types=1);

namespace App\Enums;

enum PartnerRequestStatus: string
{
    case New = 'new';
    case Reviewed = 'reviewed';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Nouvelle',
            self::Reviewed => 'Examinée',
            self::Accepted => 'Acceptée',
            self::Rejected => 'Rejetée',
            self::Archived => 'Archivée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'gray',
            self::Reviewed => 'info',
            self::Accepted => 'success',
            self::Rejected => 'danger',
            self::Archived => 'warning',
        };
    }
}
