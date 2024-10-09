<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Director;

class DirectorFactory extends Factory
{
    protected $model = Director::class; 

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(), 
            'website' => $this->faker->url(), 
        ];
    }
}
