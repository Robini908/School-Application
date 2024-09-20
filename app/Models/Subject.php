<?php

namespace App\Models;

use App\User;
use Eloquent;

class Subject extends Eloquent
{
    protected $fillable = ['subject_name', 'subject_code', 'abbreviation']; 

//     public function my_class()
// {
//     return $this->belongsTo(MyClass::class, 'my_class_id');
// }
    
}
