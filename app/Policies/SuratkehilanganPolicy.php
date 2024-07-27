<?php

namespace App\Policies;

use App\Models\User;
use App\Models\suratkehilangan;
use Illuminate\Auth\Access\Response;

class SuratkehilanganPolicy
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
    public function view(User $user, suratkehilangan $suratkehilangan): bool
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
    public function update(User $user, suratkehilangan $suratkehilangan): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, suratkehilangan $suratkehilangan): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, suratkehilangan $suratkehilangan): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, suratkehilangan $suratkehilangan): bool
    {
        //
    }
}
