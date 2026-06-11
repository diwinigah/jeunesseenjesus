<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CampEdition;
use App\Models\User;

class CampEditionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CampEdition $campEdition): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CampEdition $campEdition): bool
    {
        return true;
    }

    public function delete(User $user, CampEdition $campEdition): bool
    {
        return true;
    }
}
