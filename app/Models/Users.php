<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    public $timestamps = false;
    public function oto()
    {
        return $this->hasMany('App\Models\Photo','id');
    }

}
