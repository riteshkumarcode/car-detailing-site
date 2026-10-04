<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class RevenuePolicy
{
    public function viewRevenue(User $user): Response
    {
        return $user->isOwner() || $user->isManager() || $user->can('view_revenue')
            ? Response::allow()
            : Response::deny('Staff members do not have permission to view revenue data.');
    }
}
