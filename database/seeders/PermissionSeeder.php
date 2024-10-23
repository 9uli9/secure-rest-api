<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
       
        $superRole = Role::where("name", "superuser")->firstOrFail();
        $adminRole = Role::where("name", "admin")->firstOrFail();
        $guestRole = Role::where("name", "guest")->firstOrFail();


        $resources = ["customer", "movie", "director", "user"];
        $verbs = ["view", "viewAny", "create", "update", "delete", "restore", "forceDelete"];


        foreach ($resources as $resource) {
            foreach ($verbs as $verb) {
               
                $permission = Permission::create([
                    "name" => $verb . "-" . $resource
                ]);

               
                $superRole->assignPermission($permission);


                if ($resource !== "user") {
                    $adminRole->assignPermission($permission);
                }

               
                if ($verb === "view" || $verb === "viewAny") {
                    $guestRole->assignPermission($permission);
                }
            }
        }
    }
}
