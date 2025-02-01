<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property int $shopify_product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Inventory[] $inventories
 * @property Collection|PriceRule[] $price_rules
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'shopify_product_id' => 'int'
	];

	protected $fillable = [
		'shopify_product_id'
	];	

	public function price_rules()
	{
		return $this->hasMany(PriceRule::class);
	}
}
