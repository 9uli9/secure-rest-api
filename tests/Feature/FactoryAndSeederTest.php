<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Movie;
use App\Models\Director;
use App\Models\User;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\MovieSeeder;
use Database\Seeders\DirectorSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;

class FactoryAndSeederTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_role_seeder()
    {
        $this->seed(RoleSeeder::class);
        $this->assertDatabaseCount('roles', 4);
        $this->assertDatabaseHas('roles', ['name' => 'superuser']);
        $this->assertDatabaseHas('roles', ['name' => 'admin'    ]);
        $this->assertDatabaseHas('roles', ['name' => 'customer' ]);
        $this->assertDatabaseHas('roles', ['name' => 'director' ]);
    }

    public function test_permission_seeder()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        $this->assertDatabaseCount('permissions', 35);
    }

    public function test_user_factory()
    {
        $user = User::factory()->make();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->password);
        $this->assertNotNull($user->remember_token);

        $user = User::factory()->make();
        $this->assertDatabaseMissing('users', ['email' => $user->email]);
        
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    public function test_user_seeder()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        $this->seed(UserSeeder::class);
        $this->assertDatabaseCount('users', 4);
    }

    public function test_director_factory()
    {
        $director = Director::factory()->make();

        $this->assertInstanceOf(Director::class, $director);
        $this->assertNotNull($director->name);
        $this->assertNotNull($director->address);
        $this->assertNotNull($director->phone);
        $this->assertNotNull($director->email);

        $director = Director::factory()->make();
        $this->assertDatabaseMissing('directors', ['name' => $director->name]);

        $director = Director::factory()->create();
        $this->assertDatabaseHas('directors', ['name' => $director->name]);
    }

    public function test_director_seeder()
    {
        $this->seed(DirectorSeeder::class);
        $this->assertDatabaseCount('directors', 10);
    }

    public function test_customer_factory()
    {
        $customer = Customer::factory()->make();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertNotNull($customer->name);
        $this->assertNotNull($customer->address);
        $this->assertNotNull($customer->phone);
        $this->assertNotNull($customer->email);

        $customer = Customer::factory()->make();
        $this->assertDatabaseMissing('customers', ['name' => $customer->name]);

        $customer = Customer::factory()->create();
        $this->assertDatabaseHas('customers', ['name' => $customer->name]);
    }

    public function test_customer_seeder()
    {
        $this->seed(CustomerSeeder::class);
        $this->assertDatabaseCount('customers', 10);
    }
}
