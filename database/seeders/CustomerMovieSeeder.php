<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Movie;

class CustomerMovieSeeder extends Seeder
{
    public function run(): void
    {
        Movie::factory()
            ->count(3) // Number of Movies Seeeded into the database
            ->create()
            ->each(function ($movie) {
                // Attach cars to each movie
                Customer::factory()
                    ->count(20) // Number of customers per movie
                    ->create()
                    ->each(function ($customer) use ($movie) {
                        $movie->customers()->attach($customer->id);
                    });
            });
    }
}
