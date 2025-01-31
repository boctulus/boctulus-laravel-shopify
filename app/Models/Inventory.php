<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventory
 * 
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 *
 * @package App\Models
 */
class Inventory extends Model
{
	protected $table = 'inventory';

	protected $casts = [
		'product_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'product_id',
		'quantity'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
