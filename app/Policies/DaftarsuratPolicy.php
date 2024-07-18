<?php

namespace App\Policies;

use App\Models\User;
use App\Models\daftarsurat;
use Illuminate\Auth\Access\Response;

class DaftarsuratPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, daftarsurat $daftarsurat): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, daftarsurat $daftarsurat): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, daftarsurat $daftarsurat): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, daftarsurat $daftarsurat): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, daftarsurat $daftarsurat): bool
    {
        //
    }
}
