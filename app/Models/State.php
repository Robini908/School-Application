<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function ministry()
    {
       // return $this->hasMany(Ministry::class);
    }
}
