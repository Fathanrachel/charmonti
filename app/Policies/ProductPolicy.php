<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    // Semua bisa lihat
    public function viewAny(User $user): bool
    {
        return in_array($user->profile?->role, ['admin', 'owner']);
    }

    public function view(User $user, Product $product): bool
    {
        return in_array($user->profile?->role, ['admin', 'owner']);
    }

    // Admin dan owner bisa create, edit, delete
    public function create(User $user): bool
    {
        return in_array($user->profile?->role, ['admin', 'owner']);
    }

    public function update(User $user, Product $product): bool
    {
        return in_array($user->profile?->role, ['admin', 'owner']);
    }

    public function delete(User $user, Product $product): bool
    {
        return in_array($user->profile?->role, ['admin', 'owner']);
    }
}