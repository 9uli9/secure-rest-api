<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Director;
use App\Models\Movie;

class DirectorFactory extends Factory
{
    protected $model = Director::class;

    public function definition(): array
    {
        return [
            'name' => fake('en_GB')->company(),
            'website' => fake('en_GB')->url()
        ];
    }
}
