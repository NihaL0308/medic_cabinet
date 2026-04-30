<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isMedecin();
    }

    public function view(User $user, Service $service): bool
    {
        return $user->isAdmin() || $service->medecin_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedecin();
    }

    public function update(User $user, Service $service): bool
    {
        return $user->isAdmin() || $service->medecin_id === $user->id;
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->isAdmin() || $service->medecin_id === $user->id;
    }
}
