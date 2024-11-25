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

    protected $fillable = [
        'subject_name', 'subject_code', 'abbreviation', 'category_id', 'type', 'prerequisite_id'
    ];

    // Relationship with SubjectCategory (foreign key 'category_id')
    public function category(): BelongsTo
    {
        return $this->belongsTo(SubjectCategory::class, 'category_id');
    }

    // Self-referencing relationship for prerequisites
    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'prerequisite_id');
    }

    // Grading ranges related to this subject
    public function gradingRanges(): HasMany
    {
        return $this->hasMany(GradingRange::class);
    }

    // Grading systems related to this subject
    public function gradingSystems(): BelongsToMany
    {
        return $this->belongsToMany(GradingSystem::class, 'grading_system_subject', 'subject_id', 'grading_system_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(StudentRecord::class, 'student_subject', 'subject_id', 'student_id');
    }

    // Exam marks related to this subject
    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMarks::class);
    }
}

