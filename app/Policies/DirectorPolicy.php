<?php

namespace App\Policies;

use App\Models\Director;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Auth\Access\Response;

class DirectorPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        $superuserRole = Role::where("name", "superuser")->firstOrFail();
        $adminRole = Role::where("name", "admin")->firstOrFail();
    
        
        if ($user->hasRole($superuserRole) || $user->hasRole($adminRole)) {
            return true; 
        }
    
        return null; 
    }

    public function viewAny(User $user): bool
    {
        $permission = Permission::where("name", "viewAny-director")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function view(User $user, Director $Director): bool
    {
        $permission = Permission::where("name", "view-director")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function create(User $user): bool
    {
        $permission = Permission::where("name", "create-director")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function update(User $user, Director $director): bool
    {
        $permission = Permission::where("name", "update-director")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function delete(User $user, Director $director): bool
    {
        $permission = Permission::where("name", "delete-director")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function restore(User $user, Director $director): bool
    {
        $permission = Permission::where("name", "restore-director")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function forceDelete(User $user, Director $director): bool
    {
        $permission = Permission::where("name", "forceDelete-director")->firstOrFail();
        return $user->hasPermission($permission);
    }
}
