<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    protected $fillable = ['name', 'session', 'user_id', 'class_type_id', 'subject_id', 'master_id']; // Include master_id

    public function section()
    {
        return $this->hasMany(Section::class, 'my_class_id');
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

    /**
     * Get the class master (teacher) that belongs to the MyClass
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function master()
    {
        return $this->belongsTo(User::class, 'master_id')->where('user_type', 'teacher'); // Corrected relation for class master
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'my_class_id');
    }

    public function promotionsDemotions()
    {
        return $this->hasMany(StudentPromotionDemotion::class, 'old_class_id')->orWhere('new_class_id', $this->id);
    }
}
