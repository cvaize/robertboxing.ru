<?php

namespace App\Models\Requests;

use Illuminate\Database\Eloquent\Model;

class RequestSite extends Model
{
    protected $table = 'requests';
    protected $fillable = [
        'name',
        'phone',
    ];
}
