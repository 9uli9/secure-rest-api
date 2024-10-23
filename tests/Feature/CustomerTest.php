<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;

class CustomerTest extends TestCase
{
    use RefreshDatabase; 

    private $superUser;
    private $adminUser;
    private $guestUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        // Retrieve roles
        $superRole = Role::where('name', 'superuser')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $guestRole = Role::where('name', 'guest')->first();

        $this->superUser = $superRole->users()->first();
        $this->adminUser = $adminRole->users()->first();
        $this->guestUser = $guestRole->users()->first();

        Customer::factory()->count(10)->create();
    }

    public function test_show_all_customers(): void
    {
        $response = $this->actingAs($this->adminUser)
                         ->getJson(route('customers.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'address',
                    'dob',
                    'phone',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $customers = $response->json('data');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Customers retrieved successfully.');
    }

    public function test_customer_index_authorisation_adminuser(): void
    {
        $response = $this->actingAs($this->adminUser)
                         ->getJson(route('customers.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'address',
                    'dob',
                    'phone',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
        $success = $response->json('success');
        $message = $response->json('message');
        $customers = $response->json('data');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Customers retrieved successfully.');
    }

    public function test_customer_show(): void
    {
        $customer = Customer::factory()->create();
        $response = $this->actingAs($this->adminUser)
                         ->getJson(route('customers.show', $customer->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                    'id',
                    'name',
                    'address',
                    'dob',
                    'phone',
                    'created_at',
                    'updated_at',
            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $address = $response->json('data.address');
        $dob = $response->json('data.dob');
        $phone = $response->json('data.phone');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Customer retrieved successfully.');
        $this->assertEquals($name, $customer->name);
        $this->assertEquals($address, $customer->address);
        $this->assertEquals($dob, $customer->dob);
        $this->assertEquals($phone, $customer->phone);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id
        ]);
    }


    public function test_customer_store(): void
    {
        $customer = Customer::factory()->make();
        $response = $this->actingAs($this->adminUser)
                         ->postJson(route('customers.store'), $customer->toArray());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                    'id',
                    'name',
                    'address',
                    'dob',
                    'phone',
                    'created_at',
                    'updated_at',
            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $address = $response->json('data.address');
        $dob = $response->json('data.dob');
        $phone = $response->json('data.phone');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Customer created successfully.');
        $this->assertEquals($name, $customer->name);
        $this->assertEquals($address, $customer->address);
        $this->assertEquals($dob, $customer->dob);
        $this->assertEquals($phone, $customer->phone);

        $this->assertDatabaseHas('customers', [
            'name' => $customer->name
        ]);
    }


    public function test_customer_update(): void
    {
        $customer = Customer::factory()->create();
        $updatedCustomer = Customer::factory()->make();
        $response = $this->actingAs($this->adminUser)
                         ->putJson(route('customers.update', $customer->id), $updatedCustomer->toArray());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'address',
                'dob',
                'phone',
                'created_at',
                'updated_at',
            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $address = $response->json('data.address');
        $dob = $response->json('data.dob');
        $phone = $response->json('data.phone');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Customer updated successfully.');
        $this->assertEquals($name, $updatedCustomer->name);
        $this->assertEquals($address, $updatedCustomer->address);
        $this->assertEquals($dob, $updatedCustomer->dob);
        $this->assertEquals($phone, $updatedCustomer->phone);

        $this->assertDatabaseHas('customers', [
            'name' => $updatedCustomer->name
        ]);
    }


    public function test_customer_destroy(): void
    {
        $customer = Customer::factory()->create();
        $response = $this->actingAs($this->adminUser)
                         ->deleteJson(route('customers.destroy', $customer->id));
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $data = $response->json('data');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Customer deleted successfully.');
        $this->assertEmpty($data);

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

}
