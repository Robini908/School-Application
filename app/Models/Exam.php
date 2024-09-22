<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = ['name', 'term', 'year', 'grading_system_id', 'class_id', 'section_id'];

    // Define the relationship with GradingSystem
    public function gradingSystem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }

    // Define the relationship with MyClass
    public function myClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    // Define the relationship with Section
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    // Define the relationship with StudentRecord
    public function studentRecords(): HasMany
    {
        return $this->hasMany(StudentRecord::class);
    }
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(MyClass::class, 'exam_class_section', 'exam_id', 'class_id')
            ->withPivot('section_id');
    }
    
}
