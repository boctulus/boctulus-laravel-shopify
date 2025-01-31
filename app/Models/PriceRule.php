<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PriceRule
 * 
 * @property int $id
 * @property int $product_id
 * @property float $wholesale_price
 * @property int $minimum_quantity
 * @property float|null $discount_percentage
 * @property bool $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 *
 * @package App\Models
 */
class PriceRule extends Model
{
	protected $table = 'price_rules';

	protected $casts = [
		'product_id' => 'int',
		'wholesale_price' => 'float',
		'minimum_quantity' => 'int',
		'discount_percentage' => 'float',
		'active' => 'bool'
	];

	protected $fillable = [
		'product_id',
		'wholesale_price',
		'minimum_quantity',
		'discount_percentage',
		'active'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
