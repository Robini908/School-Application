<?php

namespace App\Models;

use App\User;
use Eloquent;
use App\Models\SubjectSelectionSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MyClass extends Model
{
    protected $fillable = ['name', 'session', 'user_id', 'class_type_id', 'subject_id']; // Include master_id

    public function section()
    {
        return $this->hasMany(Section::class, 'my_class_id');
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function studentTransitions()
    {
        return $this->hasManyThrough(
            StudentTransition::class,  // Final model
            StudentRecord::class,      // Intermediate model
            'my_class_id',             // Foreign key on StudentRecord table
            'student_id',              // Foreign key on StudentTransition table
            'id',                      // Local key on MyClass table
            'id'                       // Local key on StudentRecord table
        );
    }

    public function subjectSelectionSetting()
    {
        return $this->hasOne(SubjectSelectionSetting::class, 'class_id');
    }




    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_class_section', 'class_id', 'exam_id')
            ->withPivot('section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id')->where('user_type', 'teacher');
    }



    /**
     * Get the class master (teacher) that belongs to the MyClass
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_teacher', 'my_class_id', 'user_id')
            ->where('user_type', 'teacher') // Filter users by user_type = 'teacher'
            ->withPivot('session')          // Include the session column from the pivot table
            ->withTimestamps();             // Include timestamps if needed
    }

    /**
     * Get the class teacher for a specific session.
     */
    public function getTeacherForSession(?string $session)
    {
        if (!$session) {
            return null; // or throw an exception: throw new \InvalidArgumentException('Session is required.');
        }

        return $this->teachers()->wherePivot('session', $session)->first();
    }

    /**
     * Assign a teacher to the class for a specific session.
     */
    public function assignTeacherForSession(int $teacherId, string $session)
    {
        // Ensure the user is a teacher before assigning
        $teacher = User::where('id', $teacherId)->where('user_type', 'teacher')->firstOrFail();
        $this->teachers()->attach($teacherId, ['session' => $session]);
    }

    /**
     * Remove a teacher from the class for a specific session.
     */
    public function removeTeacherForSession(int $teacherId, string $session)
    {
        $this->teachers()->wherePivot('session', $session)->detach($teacherId);
    }


    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'my_class_id');
    }

    public function transitions()
    {
        return $this->hasMany(StudentTransition::class, 'new_class_id', 'id');
    }
}
