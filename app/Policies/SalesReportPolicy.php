<?php

namespace App\Policies;

use App\Models\SalesReport;
use App\Models\User;

class SalesReportPolicy
{
    // Hanya owner yang bisa akses laporan penjualan
    public function viewAny(User $user): bool
    {
        return $user->isOwner();
    }

    public function view(User $user, SalesReport $salesReport): bool
    {
        return $user->isOwner();
    }

    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, SalesReport $salesReport): bool
    {
        return $user->isOwner();
    }

    public function delete(User $user, SalesReport $salesReport): bool
    {
        return $user->isOwner();
    }
}