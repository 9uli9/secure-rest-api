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

        }
    }
