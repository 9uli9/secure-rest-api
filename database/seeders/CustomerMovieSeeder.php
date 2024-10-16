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
            ->count(3) // Number of movies seeded into the database
            ->create()
            ->each(function ($movie) {
                // Attach customers to each movie
                Customer::factory()
                    ->count(20) // Number of customers per movie
                    ->create()
                    ->each(function ($customer) use ($movie) {
                        $extended = fake()->boolean(50);
                        // Attach customer to the movie with additional pivot data
                        $movie->customers()->attach($customer->id, [
                            'due' => now()->addDays(7)->toDateString(), // Example due date, 7 days from now
                            'extended' => $extended         // Example boolean, default is false
                        ]);
                    });
            });
    }
}
