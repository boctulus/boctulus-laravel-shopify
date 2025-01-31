<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Charge
 * 
 * @property int $id
 * @property int $charge_id
 * @property bool $test
 * @property string|null $status
 * @property string|null $name
 * @property string|null $terms
 * @property string $type
 * @property float $price
 * @property string|null $interval
 * @property float|null $capped_amount
 * @property int|null $trial_days
 * @property Carbon|null $billing_on
 * @property Carbon|null $activated_on
 * @property Carbon|null $trial_ends_on
 * @property Carbon|null $cancelled_on
 * @property Carbon|null $expires_on
 * @property int|null $plan_id
 * @property string|null $description
 * @property int|null $reference_charge
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $user_id
 * 
 * @property Plan|null $plan
 * @property User $user
 *
 * @package App\Models
 */
class Charge extends Model
{
	use SoftDeletes;
	protected $table = 'charges';

	protected $casts = [
		'charge_id' => 'int',
		'test' => 'bool',
		'price' => 'float',
		'capped_amount' => 'float',
		'trial_days' => 'int',
		'billing_on' => 'datetime',
		'activated_on' => 'datetime',
		'trial_ends_on' => 'datetime',
		'cancelled_on' => 'datetime',
		'expires_on' => 'datetime',
		'plan_id' => 'int',
		'reference_charge' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'charge_id',
		'test',
		'status',
		'name',
		'terms',
		'type',
		'price',
		'interval',
		'capped_amount',
		'trial_days',
		'billing_on',
		'activated_on',
		'trial_ends_on',
		'cancelled_on',
		'expires_on',
		'plan_id',
		'description',
		'reference_charge',
		'user_id'
	];

	public function plan()
	{
		return $this->belongsTo(Plan::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
