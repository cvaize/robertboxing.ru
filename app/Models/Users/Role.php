<?php

namespace App\Models\Users;

use Laratrust\Models\LaratrustRole;

/**
 * App\Role
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Permission[] $permissions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereUpdatedAt($value)
 */
class Role extends LaratrustRole
{
    //
}
