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
            ->count(3) 
            ->create()
            ->each(function ($movie) {
                
                Customer::factory()
                    ->count(20) 
                    ->create()
                    ->each(function ($customer) use ($movie) {
                        $extended = fake()->boolean(50);
                        
                        $movie->customers()->attach($customer->id, [
                            'due' => now()->addDays(7)->toDateString(), 
                            'extended' => $extended         
                        ]);
                    });
            });
    }
}
