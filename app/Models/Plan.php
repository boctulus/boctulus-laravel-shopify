<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Plan
 * 
 * @property int $id
 * @property string $type
 * @property string $name
 * @property float $price
 * @property string|null $interval
 * @property float|null $capped_amount
 * @property string|null $terms
 * @property int|null $trial_days
 * @property bool $test
 * @property bool $on_install
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Charge[] $charges
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Plan extends Model
{
	protected $table = 'plans';

	protected $casts = [
		'price' => 'float',
		'capped_amount' => 'float',
		'trial_days' => 'int',
		'test' => 'bool',
		'on_install' => 'bool'
	];

	protected $fillable = [
		'type',
		'name',
		'price',
		'interval',
		'capped_amount',
		'terms',
		'trial_days',
		'test',
		'on_install'
	];

	public function charges()
	{
		return $this->hasMany(Charge::class);
	}

	public function users()
	{
		return $this->hasMany(User::class);
	}
}
