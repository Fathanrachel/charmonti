<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    // Semua bisa lihat
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    // Hanya owner yang bisa edit payment
    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->isOwner();
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->isOwner();
    }
}