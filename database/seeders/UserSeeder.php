<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superRole    = Role::where("name", "superuser")->firstOrFail();
        $adminRole    = Role::where("name", "admin"    )->firstOrFail();
        $customerRole = Role::where("name", "customer" )->firstOrFail();
        $directorRole = Role::where("name", "director" )->firstOrFail();

        $superUser = User::factory()->create();
        $superUser->assignRole($superRole);

        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $customerAdminUser = User::factory()->create();
        $customerAdminUser->assignRole($customerRole);
        
        $directorAdminUser = User::factory()->create();
        $directorAdminUser->assignRole($directorRole);
    }
}
