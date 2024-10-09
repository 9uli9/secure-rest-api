<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(), 
            'address' => $this->faker->address(), 
            'dob' => $this->faker->date('Y-m-d','-18 years'),
            'phone' => $this->faker->numerify('+353 8# ### ####'), 
        ];
    }
}
