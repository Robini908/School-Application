<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GradingSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'effective_date',
        'rules',
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'grading_system_subject', 'grading_system_id', 'subject_id');
    }

    public function gradingRanges(): HasMany
    {
        return $this->hasMany(GradingRange::class);
    }
}
