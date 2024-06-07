<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradingRange extends Model
{
    protected $fillable = ['range_from', 'range_to', 'grade'];
    protected $table = 'grading_ranges';

    use HasFactory;

    public function GradingSytem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }
}
