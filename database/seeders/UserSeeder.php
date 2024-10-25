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
        $guestRole    = Role::where("name", "guest"    )->firstOrFail();

        $testGuestUser = User::create([
            'name' => 'Guest User',
            'email' => 'guestuser@example.com',
            'password' => bcrypt('password')
        ]);

        $testAdminUser = User::create([
            'name' => 'Admin User',
            'email' => 'adminuser@example.com',
            'password' => bcrypt('password')
        ]);

        $testSuperUser = User::create([
            'name' => 'Super User',
            'email' => 'superuser@example.com',
            'password' => bcrypt('password')
        ]);


        $testGuestUser->assignRole($guestRole); 
        $testAdminUser->assignRole($adminRole);
        $testSuperUser->assignRole($superRole);  

        $superUser = User::factory()->create();
        $superUser->assignRole($superRole);

        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $guestUser = User::factory()->create();
        $guestUser->assignRole($guestRole);
    }
}
