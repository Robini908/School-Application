<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradingSystem extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function gradingRanges(): HasMany
    {
        return $this->hasMany(GradingRange::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}

