<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradingGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade', 'remark', 'gpa', 'range_from', 'range_to', 'grading_system_id',
    ];
    /**
     * Get the grading system that owns the grade.
     */
    public function gradingSystem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }
}
