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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        if (Gate::denies('viewAny', Movie::class)) {
            return $this->sendError(
                'Permission denied.', 
                ['You are not authorized to perform this action.'],
                403
            );
        }
        $movies = Movie::all();

        return $this->sendResponse(MovieResource::collection($movies), 'Movies retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            'rating' => 'required|char:1',
            'year' => 'required|string|max:4',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $movie = Movie::create($input);

        return $this->sendResponse(new MovieResource($movie), 'Movie created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $movie = Movie::find($id);

        if (is_null($movie)) {
            return $this->sendError('Movie not found.');
        }

        if (Gate::denies('view', $movie)) {
            return $this->sendError(
                'Permission denied.', 
                ['You are not authorized to perform this action.'],
                403
            );
        }

        return $this->sendResponse(new MovieResource($movie), 'Movie retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
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
            'rating' => 'required|char:1',
            'year' => 'required|string|max:4',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $movie->title = $input['title'];
        $movie->duration = $input['duration'];
        $movie->rating = $input['rating'];
        $movie->year = $input['year'];
        $movie->save();

        return $this->sendResponse(new MovieResource($movie), 'Movie updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
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
