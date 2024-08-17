<?php

namespace App\Models;
use App\User;

use Eloquent;

class MyClass extends Eloquent
{
    protected $fillable = ['name', 'session','user_id'];

    public function section()
    {
        return $this->hasMany(Section::class);
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }

     public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id')->where('user_type', 'teacher');
    } 

    

}
