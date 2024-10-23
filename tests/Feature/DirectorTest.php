<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Exceptions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Tests\TestCase;

use App\Models\Director;
use App\Models\Role;
use App\Models\User;

class DirectorTest extends TestCase
{
    // Create the database and run the migrations in each test
    use RefreshDatabase; 

    private $superUser;
    private $adminUser;
    private $guestUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    
        $superRole = Role::where('name', 'superuser')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $guestRole = Role::where('name', 'guest')->first();

        $this->superUser = $superRole->users()->first();
        $this->adminUser = $adminRole->users()->first();
        $this->guestUser = $guestRole->users()->first();

        Director::factory()->count(10)->create();

    }
    

    public function test_show_all_directors(): void
    {
        $response = $this->actingAs($this->adminUser)
                         ->getJson(route('directors.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'website'
                ]
            ]
        ]);
        $success = $response->json('success');
        $message = $response->json('message');
        $directors = $response->json('data');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Got All Directors Data Successfully!.');
    }

    public function test_director_index_authorisation_superuser(): void
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
                    'website',
               
                ]
            ]
        ]);
        $success = $response->json('success');
        $message = $response->json('message');
        $directors = $response->json('data');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Got All Directors Data Successfully!.');
    }

    public function test_show_a_single_director(): void
    {
        $director = Director::factory()->create();
        $response = $this->actingAs($this->adminUser)
                         ->getJson(route('directors.show', $director->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'website'

            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $website = $response->json('data.website');
 

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Got Director Data Successfully!.');
        $this->assertEquals($name, $director->name);
        $this->assertEquals($website, $director->website);

        $this->assertDatabaseHas('directors', [
            'id' => $director->id
        ]);
    }


    public function test_creating_a_director(): void
    {
        $director = Director::factory()->make();
        $response = $this->actingAs($this->adminUser)
                         ->postJson(route('directors.store'), $director->toArray());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'website'

            ]
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $website = $response->json('data.website');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Created Director Successfully!');
        $this->assertEquals($name, $director->name);
        $this->assertEquals($website, $director->website);

        $this->assertDatabaseHas('directors', [
            'name' => $director->name
        ]);
    }


    public function test_update_a_director(): void
    {
    
        $director = Director::factory()->create();
        $updatedDirector = Director::factory()->make(); 
    
        $response = $this->actingAs($this->adminUser)
                         ->putJson(route('directors.update', $director->id), $updatedDirector->toArray());
    
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'website'
            ]
        ]);
    
        $success = $response->json('success');
        $message = $response->json('message');
        $name = $response->json('data.name');
        $website = $response->json('data.website');
    
        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Updated Director Data Successfully!');
        $this->assertEquals($name, $updatedDirector->name);
        $this->assertEquals($website, $updatedDirector->website);
    
        $this->assertDatabaseHas('directors', [
            'name' => $updatedDirector->name
        ]);
    }
    
    

    public function test_delete_a_director(): void
    {
        $director = Director::factory()->create();
        $response = $this->actingAs($this->adminUser)
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
        $this->assertEquals($message, 'Director Was Deleted Successfully!');
        $this->assertEmpty($data);

        $this->assertDatabaseMissing('directors', [
            'id' => $director->id,
        ]);
    }

}
