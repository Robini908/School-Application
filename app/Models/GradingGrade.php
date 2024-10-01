<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradingGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grading_system_id',
        'grade',
        'remark',
        'gpa',
        'description',
        'additional_info',
    ];

    /**
     * Get the grading system that owns the grade.
     */
    public function gradingSystem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }
}
