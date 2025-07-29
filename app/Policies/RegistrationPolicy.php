<?php

namespace App\Policies;

use App\Models\{Registration, User};
use Illuminate\Auth\Access\Response;

class RegistrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Registration $registration): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Registration $registration): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Registration $registration): Response
    {
        return $user->id === $registration->user_id ||
            $user->type === 'supervisor'
            ? Response::allow()
            : Response::deny('Você não tem permissão para excluir este registro.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Registration $registration): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Registration $registration): bool
    {
        return false;
    }

    public function checkStatus(User $user, Registration $registration): Response
    {
        return $user->id === $registration->user_id ||
            $user->type === 'supervisor'
            ? Response::allow()
            : Response::deny('Você não tem permissão para verificar o status deste registro.');
    }
}
