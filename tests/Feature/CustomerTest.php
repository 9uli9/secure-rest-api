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
    private $customerUser;
    private $directorUser;

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

    public function test_customer_index(): void
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
        $this->assertCount(80, $customers);
    }

    public function test_customer_index_authorisation_superuser(): void
    {
        $response = $this->actingAs($this->superUser)
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
        $this->assertCount(80, $customers);
    }

    public function test_customer_show(): void
    {
        $customer = Customer::factory()->create();
        $response = $this->actingAs($this->customerUser)
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

    public function test_customer_show_not_found_error(): void
    {
        $missing_customer_id = mt_rand();
        while(Customer::where('id', $missing_customer_id)->count() > 0) {
                $missing_customer_id = mt_rand();
        }
        
        $response = $this->actingAs($this->customerUser)
                         ->getJson(route('customers.show', $missing_customer_id));

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Customer not found.');

        $this->assertDatabaseMissing('customers', [
            'id' => $missing_customer_id
        ]);
    }

    public function test_customer_store(): void
    {
        $customer = Customer::factory()->make();
        $response = $this->actingAs($this->customerUser)
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

    public function test_customer_store_validation_error(): void
    {
        $customer = Customer::factory()->make();
        $customer->name = '';
        $response = $this->actingAs($this->customerUser)
                         ->postJson(route('customers.store'), $customer->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data',
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Validation Error.');

        $this->assertDatabaseMissing('customers', [
            'name' => $customer->name
        ]);
    }

    public function test_customer_update(): void
    {
        $customer = Customer::factory()->create();
        $updatedCustomer = Customer::factory()->make();
        $response = $this->actingAs($this->customerUser)
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

    public function test_customer_update_validation_error(): void
    {
        $customer = Customer::factory()->create();
        $updatedCustomer = Customer::factory()->make();
        $updatedCustomer->name = '';
        $response = $this->actingAs($this->customerUser)
                         ->putJson(route('customers.update', $customer->id), $updatedCustomer->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data',
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Validation Error.');

        $this->assertDatabaseMissing('customers', [
            'name' => $updatedCustomer->name
        ]);
        $this->assertDatabaseHas('customers', [
            'name' => $customer->name
        ]);
    }

    public function test_customer_update_not_found_error(): void
    {
        $updatedCustomer = Customer::factory()->make();
        $missing_customer_id = mt_rand();
        while(Customer::where('id', $missing_customer_id)->count() > 0) {
                $missing_customer_id = mt_rand();
        }
        $response = $this->actingAs($this->customerUser)
                         ->putJson(route('customers.update', $missing_customer_id), $updatedCustomer->toArray());

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Customer not found.');

        $this->assertDatabaseMissing('customers', [
            'id' => $missing_customer_id
        ]);
    }

    public function test_customer_destroy(): void
    {
        $customer = Customer::factory()->create();
        $response = $this->actingAs($this->customerUser)
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

    public function test_customer_destroy_not_found_error(): void
    {
        $updatedCustomer = Customer::factory()->make();
        $missing_customer_id = mt_rand();
        while(Customer::where('id', $missing_customer_id)->count() > 0) {
                $missing_customer_id = mt_rand();
        }
        $response = $this->actingAs($this->customerUser)
                         ->deleteJson(route('customers.destroy', $missing_customer_id));

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Customer not found.');

        $this->assertDatabaseMissing('customers', [
            'id' => $missing_customer_id
        ]);
    }
}
