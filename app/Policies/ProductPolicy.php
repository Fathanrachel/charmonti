<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    // Semua bisa lihat
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function view(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    // Hanya owner yang bisa create, edit, delete
    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->isOwner();
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->isOwner();
    }
}