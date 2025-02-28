<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TravelOrder;

class TravelOrderPolicy
{
    public function updateStatus(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    public function view(User $user, TravelOrder $order) : bool
    {
        return $user->user_type === 'admin' || $user->id === $order->user_id;
    }
}
