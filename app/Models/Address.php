<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * 
 * @property int $id
 * @property int $user_id
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property bool $is_default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Address extends Model
{
	protected $table = 'addresses';

	protected $casts = [
		'user_id' => 'int',
		'is_default' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'street',
		'city',
		'state',
		'zip_code',
		'is_default'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
