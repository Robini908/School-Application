<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubjectCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relationship with Subject
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'category_id');
    }
}
