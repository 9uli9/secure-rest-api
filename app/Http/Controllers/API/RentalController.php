<?php

namespace App\Http\Controllers\API; 

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\CustomerMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class RentalController extends Controller
{
    public function rentMovie(Request $request): JsonResponse
    {
           // Check authorization
           if (Gate::denies('create', CustomerMovie::class)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to create rental.',
            ], 403);
        } 
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

       
        $movie = Movie::find($request->movie_id);
        
     
        foreach ($request->customers as $customerId) {
            $movie->customers()->attach($customerId, [
                'due' => $request->due,
                'extended' => $request->extended,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'You Have Successfully Rented A Movie!',
            'data' => [
                'due' => $request->due,
                'extended' => $request->extended,
                'movie_id' => $movie->id,
                'customers' => $request->customers
               
            ]
        ]);
    }
}
