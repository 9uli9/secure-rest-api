<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Movie;
use App\Models\Director;

class MovieFactory extends Factory
{
    protected $model = Movie::class; 

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), 
            'duration' => $this->faker->numberBetween(60, 180), 
            'rating' => $this->faker->randomElement(['1', '2', '3', '4', '5']), 
            'year' => $this->faker->year(), 
            'director_id' => Director::factory()
        ];
    }
}
