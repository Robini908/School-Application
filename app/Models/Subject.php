<?php

namespace App\Models;

use App\User;
use Eloquent;

class Subject extends Eloquent
{
    protected $fillable = ['subject_name', 'subject_code', 'abbreviation']; 
    
}
