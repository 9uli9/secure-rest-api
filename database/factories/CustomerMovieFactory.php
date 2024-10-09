<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CustomerMovie; 

class CustomerMovieFactory extends Factory
{
    protected $model = CustomerMovie::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'movie_id' => Movie::factory(),
            'due' => $this->faker->date, 
            'extended' => $this->faker->boolean(50), 
        ];
    }
}

