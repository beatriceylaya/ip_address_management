<?php

namespace App\Policies;

use App\Models\IpAddress;
use App\Models\User;
use App\RolesEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

use function Illuminate\Support\enum_value;

class IpAddressPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(enum_value(RolesEnum::USER));
    }

    public function view(User $user, IpAddress $ipAddress): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(enum_value(RolesEnum::USER));
    }

    public function update(User $user, IpAddress $ipAddress): bool
    {
        return $user->id === $ipAddress->user_id;
    }

    public function delete(User $user, IpAddress $ipAddress): bool
    {
        return false;
    }
}
