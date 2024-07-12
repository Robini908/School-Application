<?php

namespace App\Models;

use App\User;
use Eloquent;
use App\Models\DormMaster;

class Dorm extends Eloquent
{
    protected $fillable = ['name', 'capacity','occupancy','dorm_master','dorm_master_id'];

    public function users()
    {
        return $this-> hasOneThrough(User::class, DormMaster::class, 'user_id', 'id');
    }
}
