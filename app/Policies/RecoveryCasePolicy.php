<?php

namespace App\Policies;

use App\Models\RecoveryCase;
use App\Models\User;

class RecoveryCasePolicy
{
    /**
     * Determine whether the user can view the case.
     */
    public function view(
        User $user,
        RecoveryCase $case
    ): bool {
        return $case->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the case.
     */
    public function update(
        User $user,
        RecoveryCase $case
    ): bool {
        return $case->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the case.
     */
    public function delete(
        User $user,
        RecoveryCase $case
    ): bool {
        return $case->user_id === $user->id;
    }
}