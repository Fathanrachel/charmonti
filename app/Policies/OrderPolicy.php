<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // Semua bisa lihat dan kelola order
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function view(User $user, Order $order): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function update(User $user, Order $order): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->isOwner();
    }
}