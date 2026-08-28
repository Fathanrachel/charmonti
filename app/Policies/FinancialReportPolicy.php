<?php

namespace App\Policies;

use App\Models\FinancialReport;
use App\Models\User;

class FinancialReportPolicy
{
    // Hanya owner yang bisa akses laporan keuangan
    public function viewAny(User $user): bool
    {
        return $user->isOwner();
    }

    public function view(User $user, FinancialReport $financialReport): bool
    {
        return $user->isOwner();
    }

    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, FinancialReport $financialReport): bool
    {
        return $user->isOwner();
    }

    public function delete(User $user, FinancialReport $financialReport): bool
    {
        return $user->isOwner();
    }
}