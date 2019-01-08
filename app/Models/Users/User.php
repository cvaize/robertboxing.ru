<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Role[] $roles
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
 * @property int $id
 * @property string|null $f_name
 * @property string|null $l_name
 * @property string|null $m_name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
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
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $email_verified_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereTelegram($value)
 */
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'f_name', 'l_name', 'm_name', 'email', 'phone', 'telegram', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
