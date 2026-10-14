<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Services;

use BastienJcln\SwissCooking\Models\Role;
use BastienJcln\SwissCooking\Models\User;

class AdminService
{
    public function isAdmin(?User $user): bool
    {
        if ($user === null || $user->id_role === null) {
            return false;
        }

        $role = Role::findById((int) $user->id_role);

        return $role !== null && ($role->id === ADMIN_ROLE_ID || $role->id === MODERATOR_ROLE_ID);
    }
}