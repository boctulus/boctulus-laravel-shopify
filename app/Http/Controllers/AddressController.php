<?php

namespace App\Http\Controllers;

//uses
use App\Models\Address;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
   //traits
   protected $model = Address::class;
   protected $resource = AddressResource::class;

   protected $store_rules = [
		'user_id' => 'integer|required',
		'street' => 'string|max:255|required',
		'city' => 'string|max:255|required',
		'state' => 'string|max:255|required',
		'zip_code' => 'string|max:255|required',
		'is_default' => 'nullable|boolean',
	];

	protected $update_rules = [
		'user_id' => 'integer',
		'street' => 'string|max:255',
		'city' => 'string|max:255',
		'state' => 'string|max:255',
		'zip_code' => 'string|max:255',
		'is_default' => 'nullable|boolean',
	];


    public function index()
    {
        return $this->resource::collection($this->model::paginate())
            ->response()
            ->header('Content-Type', 'application/json');
    }

    public function store(Request $request) 
    {
        try {
            $validated = $request->validate($this->store_rules);
            $model = $this->model::create($validated);
            return (new $this->resource($model))
                ->response()
                ->header('Content-Type', 'application/json');
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $model = $this->model::findOrFail($id);
            $validated = $request->validate($this->update_rules);
            $model->update($validated);
            return new $this->resource($model);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'id' => $id
            ], 404);
        }
    }

   public function show($id)
   {
        try {
            $model = $this->model::findOrFail($id);
            return new $this->resource($model);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'id' => $id
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $model = $this->model::findOrFail($id);
            $model->delete();
            return response()->noContent();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'id' => $id
            ], 404);
        }
    }
}