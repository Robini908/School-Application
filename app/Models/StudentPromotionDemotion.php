<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPromotionDemotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_record_id',
        'old_class_id',
        'new_class_id',
        'old_section_id',
        'new_section_id',
        'type',
        'reason',
        'event_date',
        'repetition_count',
        'academic_year',
        'previous_academic_year',
        'next_academic_year',
        'approved_by',
        'remarks',
    ];

    public function studentRecord()
    {
        return $this->belongsTo(StudentRecord::class);
    }

    public function oldClass()
    {
        return $this->belongsTo(MyClass::class, 'old_class_id');
    }

    public function newClass()
    {
        return $this->belongsTo(MyClass::class, 'new_class_id');
    }

    public function oldSection()
    {
        return $this->belongsTo(Section::class, 'old_section_id');
    }

    public function newSection()
    {
        return $this->belongsTo(Section::class, 'new_section_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
