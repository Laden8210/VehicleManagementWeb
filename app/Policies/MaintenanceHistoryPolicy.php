<?php

namespace App\Policies;

use App\Models\MaintenanceHistory;
use App\Models\User;

class MaintenanceHistoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Driver');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceHistory $repairRequest): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Driver');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Driver');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenanceHistory $repairRequest): bool
    {
        return $user->hasRole('Driver');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceHistory $repairRequest): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Driver');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MaintenanceHistory $repairRequest): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Driver');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MaintenanceHistory $repairRequest): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Driver');
    }
}
