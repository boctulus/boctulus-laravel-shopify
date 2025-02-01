<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $shopify_grandfathered
 * @property string|null $shopify_namespace
 * @property bool $shopify_freemium
 * @property int|null $plan_id
 * @property string|null $deleted_at
 * @property Carbon|null $password_updated_at
 * @property int|null $theme_support_level
 * 
 * @property Plan|null $plan
 * @property Collection|Address[] $addresses
 * @property Collection|Cart[] $carts 
 * @property Collection|Favorite[] $favorites
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use HasApiTokens, HasRoles, SoftDeletes, Notifiable, HasFactory;
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'shopify_grandfathered' => 'bool',
		'shopify_freemium' => 'bool',
		'plan_id' => 'int',
		'password_updated_at' => 'datetime',
		'theme_support_level' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'shopify_grandfathered',
		'shopify_namespace',
		'shopify_freemium',
		'plan_id',
		'password_updated_at',
		'theme_support_level'
	];

	public function plan()
	{
		return $this->belongsTo(Plan::class);
	}

	public function addresses()
	{
		return $this->hasMany(Address::class);
	}

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function favorites()
	{
		return $this->hasMany(Favorite::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
