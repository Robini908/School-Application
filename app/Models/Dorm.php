<?php

namespace App\Models;

use App\User;
use Eloquent;
use App\Models\DormMaster;
use Illuminate\Database\Eloquent\Model;

class Dorm extends Model
{
    protected $fillable = ['name', 'capacity','user_id','session'];

    

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id')->where('user_type', 'teacher');
    } 

    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }
    
}
