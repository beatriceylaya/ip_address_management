<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\IpAddress;

class IpAddressPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, IpAddress $ipAddress): bool
    {
        return $user->id === $ipAddress->user_id;
    }

    public function create(User $user): bool
    {
        return true;
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
