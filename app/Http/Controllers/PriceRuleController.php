<?php

namespace App\Http\Controllers;

//uses
use App\Models\PriceRule;
use App\Http\Resources\PriceRuleResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PriceRuleController extends Controller
{
   //traits
   protected $model = PriceRule::class;
   protected $resource = PriceRuleResource::class;

   protected $store_rules = [
		'product_id' => 'integer|required',
		'wholesale_price' => 'required',
		'minimum_quantity' => 'integer|required',
		'discount_percentage' => 'nullable',
		'active' => 'nullable|boolean',
	];

	protected $update_rules = [
		'product_id' => 'integer',
		'wholesale_price' => '',
		'minimum_quantity' => 'integer',
		'discount_percentage' => 'nullable',
		'active' => 'nullable|boolean',
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