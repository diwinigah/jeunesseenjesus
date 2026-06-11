<?php

declare(strict_types=1);

namespace App\Enums;

enum InvestorType: string
{
    case Individual = 'individual';
    case Organization = 'organization';
}
