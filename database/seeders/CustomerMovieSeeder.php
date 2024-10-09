<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Movie;

class CustomerMovieSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all(); 
        $movies = Movie::all(); 
    
        foreach ($customers as $customer) {
            $customer->movies()->attach(
                $movies->random(rand(1, 3))->pluck('id')->toArray() 
            );
        }
    }
}
