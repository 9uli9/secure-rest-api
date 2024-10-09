<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superRole    = Role::where("name", "superuser")->firstOrFail();
        $adminRole    = Role::where("name", "admin"    )->firstOrFail();
        $customerRole = Role::where("name", "customer" )->firstOrFail();
        $directorRole = Role::where("name", "director" )->firstOrFail();

        $resources = [
            "customer", "order", "product", "director", "user"
        ];
        $verbs = [
            "view", "viewAny", "create", "update", "delete", "restore", "forceDelete"
        ];

        foreach ($resources as $resource) {
            foreach ($verbs as $verb) {
                $permission = \App\Models\Permission::create([
                    "name" => $verb . "-" . $resource
                ]);
                if ($resource == "customer" || $resource == "order") {
                    $customerRole->assignPermission($permission);
                }
                else if ($resource == "director" || $resource == "product") {
                    $directorRole->assignPermission($permission);
                }
                else {
                    $adminRole->assignPermission($permission);
                }
            }
        }
    }
}
