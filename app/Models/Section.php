<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    protected $fillable = ['name', 'my_class_id', 'active', 'teacher_id'];

    // Define the relationship with MyClass
    public function my_class(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }
    

    // Define the relationship with Teacher
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Define the relationship with StudentRecord
    public function studentRecords(): HasMany
    {
        return $this->hasMany(StudentRecord::class);
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_class_section', 'section_id', 'exam_id')
            ->withPivot('class_id');
    }
    
}
