<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['type', 'description'];
}
