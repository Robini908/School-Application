<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    // Add 'category_id' to the fillable attributes
    protected $fillable = ['subject_name', 'subject_code', 'abbreviation', 'category_id',];

    public function gradingRanges(): HasMany
    {
        return $this->hasMany(GradingRange::class);
    }

    public function gradingSystems(): BelongsToMany
    {
        return $this->belongsToMany(GradingSystem::class, 'grading_system_subject', 'subject_id', 'grading_system_id');
    }

    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMarks::class);
    }

    // Relationship with SubjectCategory
    public function category()
    {
        return $this->belongsTo(SubjectCategory::class, 'category_id');
    }
    
}
