<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Auth\Access\Response;

class MoviePolicy
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
        $permission = Permission::where("name", "viewAny-movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function view(User $user, Movie $movie): bool
    {
        $permission = Permission::where("name", "view-movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function create(User $user): bool
    {
        $permission = Permission::where("name", "create-movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function update(User $user, Movie $movie): bool
    {
        $permission = Permission::where("name", "update-movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function delete(User $user, Movie $movie): bool
    {
        $permission = Permission::where("name", "delete-movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function restore(User $user, Movie $movie): bool
    {
        $permission = Permission::where("name", "restore-movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function forceDelete(User $user, Movie $movie): bool
    {
        $permission = Permission::where("name", "forceDelete-movie")->firstOrFail();
        return $user->hasPermission($permission);
    }
}
