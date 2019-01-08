<?php

namespace App\Models\Users;

use Laratrust\Models\LaratrustPermission;

/**
 * App\Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereUpdatedAt($value)
 */
class Permission extends LaratrustPermission
{
    //
}
