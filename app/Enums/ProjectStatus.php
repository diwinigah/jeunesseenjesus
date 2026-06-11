<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Funded = 'funded';
    case Archived = 'archived';
}
