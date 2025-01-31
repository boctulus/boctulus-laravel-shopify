<?php

namespace App\Http\Controllers;

//uses
use App\Models\Cart;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;

class CartController extends Controller
{
   //traits
   protected $model = Cart::class;
   protected $resource = CartResource::class;

   // __VALIDATION_RULES__

   public function index()
   {
       return $this->resource::collection($this->model::paginate());
   }

   public function store(Request $request) 
   {
       $validated = $request->validate($this->store_rules);
       $model = $this->model::create($validated);
       return new $this->resource($model);
   }

   public function show($id)
   {
       $model = $this->model::findOrFail($id);
       return new $this->resource($model);
   }

   public function update(Request $request, $id)
   {
       $model = $this->model::findOrFail($id);
       $validated = $request->validate($this->update_rules);
       $model->update($validated);
       return new $this->resource($model);
   }

   public function destroy($id)
   {
       $this->model::findOrFail($id)->delete();
       return response()->noContent();
   }
}