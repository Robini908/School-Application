<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    // Include fillable attributes
    protected $fillable = ['name', 'my_class_id', 'active', 'teacher_id', 'session_year'];

    /**
     * Define the relationship with MyClass.
     * Each section belongs to a specific class.
     */
    public function my_class(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    /**
     * Define the relationship with the Teacher.
     * Each section can have one teacher assigned.
     * Make sure to use the correct foreign key here.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id') // Corrected foreign key to 'teacher_id'
            ->where('user_type', 'teacher'); // Assuming 'user_type' indicates if the user is a teacher
    }

    public function promotionsDemotions()
    {
        return $this->hasMany(StudentPromotionDemotion::class, 'old_section_id')->orWhere('new_section_id', $this->id);
    }

    /**
     * Define the relationship with StudentRecord.
     * Each section can have multiple student records.
     */
    public function studentRecords(): HasMany
    {
        return $this->hasMany(StudentRecord::class);
    }

    /**
     * Define the relationship with Exams.
     * Each section can be linked to multiple exams.
     */
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_class_section', 'section_id', 'exam_id')
            ->withPivot('class_id');
    }
    
}
