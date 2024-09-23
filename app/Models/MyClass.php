<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MyClass extends Eloquent
{
    protected $fillable = ['name', 'session', 'user_id', 'class_type_id', 'subject_id'];

    public function section()
    {
        return $this->hasMany(Section::class, 'my_class_id'); // Use 'my_class_id' as the foreign key
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
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



    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'my_class_id');
    }
}
