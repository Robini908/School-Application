<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['subject_name', 'subject_code', 'abbreviation'];

    public function gradingRanges(): HasMany
    {
        return $this->hasMany(GradingRange::class);
    }

    public function gradingSystems(): BelongsToMany
    {
        return $this->belongsToMany(GradingSystem::class, 'grading_system_subject', 'subject_id', 'grading_system_id');
    }
}
