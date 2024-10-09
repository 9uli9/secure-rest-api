<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\MovieResource;
use App\Models\Movie;

use Validator;

class MovieController extends BaseController
{
    public function index(): JsonResponse
    {
        if (Gate::denies('viewAny', Movie::class)) {
            return $this->sendError(
                'Permission denied.',
                ['You are not authorized to perform this action.'],
                403
            );
        }
        $movies = Movie::with('director')->get(); 

        return $this->sendResponse(MovieResource::collection($movies), 'Movies Loaded successfully.');
    }

   
    public function store(Request $request): JsonResponse
    {
        if (Gate::denies('create', Movie::class)) {
            return $this->sendError(
                'Permission denied.',
                ['You are not authorized to perform this action.'],
                403
            );
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
            'duration' => 'required|integer',
            'rating' => 'required|string|size:1|in:1,2,3,4,5',
            'year' => 'required|string|max:4',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $movie = Movie::create($input);

        return $this->sendResponse(new MovieResource($movie), 'Movie created successfully.', 201);
    }

    public function show(Movie $movie): JsonResponse
    {
        if (Gate::denies('view', $movie)) {
            return $this->sendError(
                'Permission denied.',
                ['You are not authorized to perform this action.'],
                403
            );
        }

        return $this->sendResponse(new MovieResource($movie), 'Movie retrieved successfully.');
    }

    public function update(Request $request, Movie $movie): JsonResponse
    {
        if (Gate::denies('update', $movie)) {
            return $this->sendError(
                'Permission denied.',
                ['You are not authorized to perform this action.'],
                403
            );
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
            'duration' => 'required|integer',
            'rating' => 'required|string|size:1|in:1,2,3,4,5',
            'year' => 'required|string|max:4',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $movie->update($input);

        return $this->sendResponse(new MovieResource($movie), 'Movie updated successfully.');
    }

    public function destroy(Movie $movie): JsonResponse
    {
        if (Gate::denies('delete', $movie)) {
            return $this->sendError(
                'Permission denied.',
                ['You are not authorized to perform this action.'],
                403
            );
        }

        $movie->delete();

        return $this->sendResponse([], 'Movie deleted successfully.');
    }
}
