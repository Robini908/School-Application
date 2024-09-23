<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    // Specify the table if it doesn't follow the Laravel naming convention
    protected $table = 'user_types';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'title', 'name', 'level',
    ];

    // Define a relationship with the User model
    public function users()
    {
        return $this->hasMany(User::class, 'user_type', 'title');
    }
}
