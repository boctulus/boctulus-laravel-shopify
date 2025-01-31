<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CartItem
 * 
 * @property int $id
 * @property int $cart_id
 * @property int $shopify_product_id
 * @property int $quantity
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Cart $cart
 *
 * @package App\Models
 */
class CartItem extends Model
{
	protected $table = 'cart_items';

	protected $casts = [
		'cart_id' => 'int',
		'shopify_product_id' => 'int',
		'quantity' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'cart_id',
		'shopify_product_id',
		'quantity',
		'price'
	];

	public function cart()
	{
		return $this->belongsTo(Cart::class);
	}
}
