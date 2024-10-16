<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Exceptions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Tests\TestCase;

use App\Models\Director;
use App\Models\Role;

class DirectorTest extends TestCase
{
    // Create the database and run the migrations in each test
    use RefreshDatabase; 

    private $superUser;
    private $customerUser;
    private $directorUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $superRole = Role::where('name', 'superuser')->first();
        $customerRole = Role::where('name', 'customer')->first();
        $directorRole = Role::where('name', 'director')->first();

        $this->superUser = $superRole->users()->first();
        $this->customerUser = $customerRole->users()->first();
        $this->directorUser = $directorRole->users()->first();
    }

    public function test_director_index(): void
    {
        $response = $this->actingAs($this->directorUser)
                         ->getJson(route('directors.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'address',
                    'phone',
                    'email',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
        $success = $response->json('success');
        $message = $response->json('message');
        $directors = $response->json('data');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'directors retrieved successfully.');
        $this->assertCount(10, $directors);
    }

    public function test_director_index_authorisation_fail(): void
    {
        $response = $this->actingAs($this->customerUser)
                         ->getJson(route('directors.index'));

        $response->assertStatus(403);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
        $success = $response->json('success');
        $message = $response->json('message');

        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Permission denied.');
    }

    public function test_director_index_authorisation_super(): void
    {
        $response = $this->actingAs($this->superUser)
                         ->getJson(route('directors.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'address',
                    'phone',
                    'email',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
        $success = $response->json('success');
        $message = $response->json('message');
        $directors = $response->json('data');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'directors retrieved successfully.');
        $this->assertCount(10, $directors);
    }

    public function test_director_show(): void
    {
        $director = Director::factory()->create();
        $response = $this->actingAs($this->directorUser)
                         ->getJson(route('directors.show', $director->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'address',
                'phone',
                'email',
                'created_at',
                'updated_at',
            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $address = $response->json('data.address');
        $phone = $response->json('data.phone');
        $email = $response->json('data.email');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'director retrieved successfully.');
        $this->assertEquals($name, $director->name);
        $this->assertEquals($address, $director->address);
        $this->assertEquals($phone, $director->phone);
        $this->assertEquals($email, $director->email);

        $this->assertDatabaseHas('directors', [
            'id' => $director->id
        ]);
    }

    public function test_director_show_not_found_error(): void
    {
        $missing_director_id = mt_rand();
        while(Director::where('id', $missing_director_id)->count() > 0) {
                $missing_director_id = mt_rand();
        }
        
        $response = $this->actingAs($this->directorUser)
                         ->getJson(route('directors.show', $missing_director_id));

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'director not found.');

        $this->assertDatabaseMissing('directors', [
            'id' => $missing_director_id
        ]);
    }

    public function test_director_store(): void
    {
        $director = Director::factory()->make();
        $response = $this->actingAs($this->directorUser)
                         ->postJson(route('directors.store'), $director->toArray());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'address',
                'phone',
                'email',
                'created_at',
                'updated_at',
            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $address = $response->json('data.address');
        $phone = $response->json('data.phone');
        $email = $response->json('data.email');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'director created successfully.');
        $this->assertEquals($name, $director->name);
        $this->assertEquals($address, $director->address);
        $this->assertEquals($phone, $director->phone);
        $this->assertEquals($email, $director->email);

        $this->assertDatabaseHas('directors', [
            'name' => $director->name
        ]);
    }

    public function test_director_store_validation_error(): void
    {
        $director = Director::factory()->make();
        $director->name = '';
        $response = $this->actingAs($this->directorUser)
                         ->postJson(route('directors.store'), $director->toArray());

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

        $this->assertDatabaseMissing('directors', [
            'name' => $director->name
        ]);
    }

    public function test_director_update(): void
    {
        $director = Director::factory()->create();
        $updateddirector = Director::factory()->make();
        $response = $this->actingAs($this->directorUser)
                         ->putJson(route('directors.update', $director->id), $updateddirector->toArray());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'address',
                'phone',
                'email',
                'created_at',
                'updated_at',
            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $address = $response->json('data.address');
        $phone = $response->json('data.phone');
        $email = $response->json('data.email');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'director updated successfully.');
        $this->assertEquals($name, $updatedDirector->name);
        $this->assertEquals($address, $updatedDirector->address);
        $this->assertEquals($phone, $updatedDirector->phone);
        $this->assertEquals($email, $updatedDirector->email);

        $this->assertDatabaseHas('directors', [
            'name' => $updatedDirector->name
        ]);
    }

    public function test_director_update_validation_error(): void
    {
        $director = Director::factory()->create();
        $updatedDirector = Director::factory()->make();
        $updatedDirector->name = '';
        $response = $this->actingAs($this->directorUser)
                         ->putJson(route('directors.update', $director->id), $updatedDirector->toArray());

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

        $this->assertDatabaseMissing('directors', [
            'name' => $updatedDirector->name
        ]);
        $this->assertDatabaseHas('directors', [
            'name' => $director->name
        ]);
    }

    public function test_director_update_not_found_error(): void
    {
        $updatedDirector = Director::factory()->make();
        $missing_director_id = mt_rand();
        while(Director::where('id', $missing_director_id)->count() > 0) {
                $missing_director_id = mt_rand();
        }
        $response = $this->actingAs($this->directorUser)
                         ->putJson(route('directors.update', $missing_director_id), $updatedDirector->toArray());

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'director not found.');

        $this->assertDatabaseMissing('directors', [
            'id' => $missing_director_id
        ]);
    }

    public function test_director_destroy(): void
    {
        $director = Director::factory()->create();
        $response = $this->actingAs($this->directorUser)
                         ->deleteJson(route('directors.destroy', $director->id));
        
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
        $this->assertEquals($message, 'director deleted successfully.');
        $this->assertEmpty($data);

        $this->assertDatabaseMissing('directors', [
            'id' => $director->id,
        ]);
    }

    public function test_director_destroy_not_found_error(): void
    {
        $updatedDirector = Director::factory()->make();
        $missing_director_id = mt_rand();
        while(Director::where('id', $missing_director_id)->count() > 0) {
                $missing_director_id = mt_rand();
        }
        $response = $this->actingAs($this->directorUser)
                         ->deleteJson(route('directors.destroy', $missing_director_id));

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'director not found.');

        $this->assertDatabaseMissing('directors', [
            'id' => $missing_director_id
        ]);
    }
}
