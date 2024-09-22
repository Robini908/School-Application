<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradingRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'range_from',
        'range_to',
        'grade',
        'remark',
        'gpa',
        'grading_system_id', // Ensure this is included for the relationship
        'subject_id',
    ];

    protected $table = 'grading_ranges';

    public function gradingSystem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
