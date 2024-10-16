<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\DirectorResource;
use App\Models\Director;

use Validator;

class DirectorController extends BaseController
{
    public function index(): JsonResponse
    {
        if (Gate::denies('viewAny', Director::class)) {
            return $this->sendError(
                'Permission denied.', 
                ['You are not authorized to perform this action.'],
                403
            );
        }
        $directors = Director::all();
    
        return $this->sendResponse(
            DirectorResource::collection($directors), 
            'directors retrieved successfully.'
        );
    }

    public function store(Request $request): JsonResponse
    {
        if (Gate::denies('create', Director::class)) {
            return $this->sendError(
                'Permission denied.', 
                ['You are not authorized to perform this action.'],
                403
            );
        }
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'website' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
   
        $director = Director::create($input);
   
        return $this->sendResponse(new DirectorResource($director), 'director created successfully.');
    }

    public function show(string $id): JsonResponse
    {
        $director = Director::find($id);
          if (is_null($director)) {
            return $this->sendError('Director not found.');
        }
   
        if (Gate::denies('view', $director)) {
            return $this->sendError(
                'Permission denied.', 
                ['You are not authorized to perform this action.'],
                403
            );
        }

        return $this->sendResponse(new DirectorResource($director), 'director retrieved successfully.');
    }

    public function update(Request $request, Director $director): JsonResponse
    {
        if (Gate::denies('update', $director)) {
            return $this->sendError(
                'Permission denied.', 
                ['You are not authorized to perform this action.'],
                403
            );
        }
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'website' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
   
        $director->name = $input['name'];
        $director->website = $input['website'];
        $director->save();
   
        return $this->sendResponse(new DirectorResource($director), 'director updated successfully.');
    }

    public function destroy(Director $director): JsonResponse
    {
        if (Gate::denies('delete', $director)) {
            return $this->sendError(
                'Permission denied.', 
                ['You are not authorized to perform this action.'],
                403
            );
        }
        $director->delete();

        return $this->sendResponse([], 'director deleted successfully.');
    }
}
