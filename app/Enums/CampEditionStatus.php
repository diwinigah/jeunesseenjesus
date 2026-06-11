<?php

declare(strict_types=1);

namespace App\Enums;

enum CampEditionStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
    case Archived = 'archived';
}
