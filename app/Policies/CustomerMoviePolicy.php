<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Movie;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Auth\Access\Response;

class CustomerMoviePolicy
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
        $permission = Permission::where("name", "viewAny-customer_movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function view(User $user, Customer $customer): bool
    {
        $permission = Permission::where("name", "view-customer_movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function create(User $user): bool
    {
        $permission = Permission::where("name", "create-customer_movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function update(User $user, Customer $customer): bool
    {
        $permission = Permission::where("name", "update-customer_movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function delete(User $user, Customer $customer): bool
    {
        $permission = Permission::where("name", "delete-customer_movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function restore(User $user, Customer $customer): bool
    {
        $permission = Permission::where("name", "restore-customer_movie")->firstOrFail();
        return $user->hasPermission($permission);
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        $permission = Permission::where("name", "forceDelete-customer_movie")->firstOrFail();
        return $user->hasPermission($permission);
    }
}
