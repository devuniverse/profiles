<?php

namespace Devuniverse\Profiles\Models;

use Illuminate\Database\Eloquent\Model;

class Usermeta extends Model
{
    protected $table='usermeta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','meta_key', 'meta_value'
    ];
}
