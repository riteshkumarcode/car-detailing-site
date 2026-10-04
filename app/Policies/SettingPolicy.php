<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SettingPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->isOwner() || $user->can('manage_settings')
            ? Response::allow()
            : Response::deny('Only the studio owner can view system settings.');
    }

    public function view(User $user, Setting $setting): Response
    {
        return $this->viewAny($user);
    }

    public function create(User $user): Response
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Setting $setting): Response
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Setting $setting): Response
    {
        return $this->viewAny($user);
    }
}
