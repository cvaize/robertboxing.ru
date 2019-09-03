<?php

namespace App\Models\Users;

use App\Models\Requests\Feedback;
use App\Models\Requests\RequestSite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Permission[]                                   $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Role[]                                         $roles
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePermissionIs($permission = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereRoleIs($role = '')
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\User withoutTrashed()
 * @mixin \Eloquent
 * @property int                                                                                                            $id
 * @property string|null                                                                                                    $f_name
 * @property string|null                                                                                                    $l_name
 * @property string|null                                                                                                    $m_name
 * @property string                                                                                                         $email
 * @property string                                                                                                         $password
 * @property string|null                                                                                                    $remember_token
 * @property \Illuminate\Support\Carbon|null                                                                                $created_at
 * @property \Illuminate\Support\Carbon|null                                                                                $updated_at
 * @property string|null                                                                                                    $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereFName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereLName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereMName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereUpdatedAt($value)
 * @property string|null                                                                                                    $phone
 * @property string|null                                                                                                    $telegram
 * @property string|null                                                                                                    $email_verified_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereTelegram($value)
 * @property int|null                                                                                                       $is_smsable
 * @property int|null                                                                                                       $is_emailable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Requests\RequestSite[]                               $requests
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereIsEmailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereIsSmsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User emailable()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User notifiable()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Requests\Feedback[] $feedback
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User search($value)
 */
class User extends Authenticatable {
	use LaratrustUserTrait;
	use Notifiable;
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'f_name', 'l_name', 'm_name', 'email', 'phone', 'telegram', 'is_smsable', 'is_emailable', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
			'password', 'remember_token',
	];

	/**
	 * @return null|string
	 */
	public function getFirstName(): ?string {
		return $this->{'f_name'};
	}

	/**
	 * @return null|string
	 */
	public function getLastName(): ?string {
		return $this->{'l_name'};
	}

	/**
	 * @return null|string
	 */
	public function getMiddleName(): ?string {
		return $this->{'m_name'};
	}

	/**
	 * @return string
	 */
	public function getEmail(): ?string {
		return $this->{'email'};
	}

	/**
	 * @return null|string
	 */
	public function getPhone(): ?string {
		return $this->{'phone'};
	}

	/**
	 * @return null|string
	 */
	public function getTelegram(): ?string {
		return $this->{'telegram'};
	}

	/**
	 * @return bool
	 */
	public function isSmsable(): bool {
		return (null === $this->{'is_smsable'} || false == $this->{'is_smsable'}) ? false : true;
	}

	/**
	 * @return bool
	 */
	public function isEmailable(): bool {
		return (null === $this->{'is_emailable'} || false == $this->{'is_emailable'}) ? false : true;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function requests() {
		return $this->hasMany(RequestSite::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function feedback() {
		return $this->hasMany(Feedback::class);
	}

	/**
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeNotifiable(Builder $query): Builder {
		return $query->where('is_emailable', '=', '1')
			->orWhere('is_smsable', '=', '1');
	}

	/**
	 * Route notifications for the smscRU channel.
	 *
	 * @return string
	 */
	public function routeNotificationForSmscru() {
		return $this->getPhone();
	}

	/**
	 * @param Builder $query
	 * @param string  $value
	 * @return Builder
	 */
	public function scopeSearch(Builder $query, string $value): Builder {
		return $query->where(function($query) use ($value) {
			/**
			 * @var Builder $query
			 */
			$query->orWhere('f_name', 'like', '%' . $value . '%')
				->orWhere('l_name', 'like', '%' . $value . '%')
				->orWhere('m_name', 'like', '%' . $value . '%')
				->orWhere('phone', 'like', '%' . $value . '%')
				->orWhere('email', 'like', '%' . $value . '%');
		});
	}

	/**
	 * @param Builder $query
	 * @param array   $frd
	 * @return Builder
	 */
	public function scopeFilter(Builder $query, array $frd): Builder {
		foreach ($frd as $key => $value) {
			if (null === $value)
				continue;

			switch($key) {
				case 'search': {
					$query->search($value);
				} break;
				default: break;
			}
		}

		return $query;
	}
}
