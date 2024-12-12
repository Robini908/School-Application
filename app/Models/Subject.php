<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_name',
        'subject_code',
        'abbreviation',
        'category_id',
        'type',
        'prerequisite_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SubjectCategory::class, 'category_id');
    }

    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'prerequisite_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(StudentRecord::class, 'student_subject', 'subject_id', 'student_id')
            ->withTimestamps();
    }

    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMarks::class, 'subject_id');
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

    public static function getSubjectsGroupedByCategoryAndType()
    {
        $subjects = self::with('category')->get();

        $grouped = $subjects->groupBy([
            function ($subject) {
                return $subject->type; // Group by type (e.g., compulsory, elective)
            },
            function ($subject) {
                return $subject->category->name ?? 'Uncategorized'; // Group by category
            },
        ]);

        return $grouped;
    }
}
