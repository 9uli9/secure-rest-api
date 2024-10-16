<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\MovieSeeder;
use Tests\TestCase;

use App\Models\Movie;

class MovieTest extends TestCase
{
    use RefreshDatabase;
    public function it_can_create_a_movie()
    {
        $response = $this->postJson('/api/movies', [
            'title' => 'Shrek 1',
            'duration' => '120',
            'rating' => 5,
            'year' => '2002'
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Movie created successfully.']);
    }


    public function it_can_list_movies()
    {
        Movie::factory()->count(3)->create();

        $response = $this->getJson('/api/movies');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
