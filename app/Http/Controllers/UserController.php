<?php

namespace App\Http\Controllers;

//uses
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
   //traits
   protected $model = User::class;
   protected $resource = UserResource::class;

   protected $store_rules = [
		'name' => 'string|max:255|required',
		'email' => 'string|max:255|unique:users,email|required',
		'email_verified_at' => 'nullable',
		'password' => 'string|max:255|required',
		'remember_token' => 'nullable|string|max:100',
		'shopify_grandfathered' => 'nullable|boolean',
		'shopify_namespace' => 'nullable|string|max:255',
		'shopify_freemium' => 'nullable|boolean',
		'plan_id' => 'nullable|integer',
		'deleted_at' => 'nullable',
		'password_updated_at' => 'nullable|date',
		'theme_support_level' => 'nullable|integer',
	];

	protected $update_rules = [
		'name' => 'string|max:255',
		'email' => 'string|max:255',
		'email_verified_at' => 'nullable',
		'password' => 'string|max:255',
		'remember_token' => 'nullable|string|max:100',
		'shopify_grandfathered' => 'nullable|boolean',
		'shopify_namespace' => 'nullable|string|max:255',
		'shopify_freemium' => 'nullable|boolean',
		'plan_id' => 'nullable|integer',
		'deleted_at' => 'nullable',
		'password_updated_at' => 'nullable|date',
		'theme_support_level' => 'nullable|integer',
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