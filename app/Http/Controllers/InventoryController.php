<?php

namespace App\Http\Controllers;

//uses
use App\Models\Inventory;
use App\Http\Resources\InventoryResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
   //traits
   protected $model = Inventory::class;
   protected $resource = InventoryResource::class;

   protected $store_rules = [
		'product_id' => 'integer|required',
		'quantity' => 'nullable|integer',
	];

	protected $update_rules = [
		'product_id' => 'integer',
		'quantity' => 'nullable|integer',
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