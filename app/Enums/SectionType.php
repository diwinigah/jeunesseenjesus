<?php

declare(strict_types=1);

namespace App\Enums;

enum SectionType: string
{
    case College = 'college';
    case Lycee = 'lycee';
    case Universitaire = 'universitaire';
    case Adulte = 'adulte';
    case Invite = 'invite';
    case Famille = 'famille';

    public function label(): string
    {
        return match ($this) {
            self::College => 'College',
            self::Lycee => 'Lycee',
            self::Universitaire => 'Universitaire',
            self::Adulte => 'Adulte',
            self::Invite => 'Invite',
            self::Famille => 'Famille',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::College => 'info',
            self::Lycee => 'success',
            self::Universitaire => 'primary',
            self::Adulte => 'warning',
            self::Invite => 'gray',
            self::Famille => 'danger',
        };
    }
}
