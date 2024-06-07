<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingSytem extends Model
{
    use HasFactory;
    protected $fillable = ['range_from','range_to','grade'];
}
