<?php

namespace App\Http\Controllers\API; 

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Movie;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;


class RentalController extends Controller
{
    public function rentMovie(Request $request): JsonResponse
    {

        // Check authorization
        if (Gate::denies('create', Rental::class)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to create rental.',
            ], 403);
        } 
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'due' => 'required|date', 
            'extended' => 'boolean', 
            'movie_id' => 'required|exists:movies,id',
            'customers' => 'required|array',
            'customers.*' => 'exists:customers,id' 
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 422);
        }

        // Find the movie and attach customers
        $movie = Movie::find($request->movie_id);
        
        // Attach customers with the additional fields to the pivot table
        foreach ($request->customers as $customerId) {
            $movie->customers()->attach($customerId, [
                'due' => $request->due,
                'extended' => $request->extended,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customers have successfully rented the movie.',
            'data' => [
                'due' => $request->due,
                'extended' => $request->extended,
                'movie_id' => $movie->id,
                'customers' => $request->customers
               
            ]
        ]);
    }
}