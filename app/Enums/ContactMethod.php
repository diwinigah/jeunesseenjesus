<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactMethod: string
{
    case Phone = 'phone';
    case Whatsapp = 'whatsapp';
    case Email = 'email';
}
