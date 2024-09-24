<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMarks extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_id',
        'grading_range_id',
        'marks',
    ];

    /**
     * Relationship with StudentRecord
     */
    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }

    /**
     * Relationship with Exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Relationship with Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relationship with GradingRange
     */
    public function gradingRange()
    {
        return $this->belongsTo(GradingRange::class, 'grading_range_id');
    }
    public function studentRecord()
    {
        return $this->hasOne(StudentRecord::class, 'id', 'student_id');
    }

    /**
     * Get the full name of the student.
     * You can customize this based on your StudentRecord model.
     */
    public function getStudentFullNameAttribute()
    {
        return "{$this->student->first_name} {$this->student->last_name}";
    }

    /**
     * Calculate the percentage of marks (assuming max marks is defined).
     */
    public function getPercentageAttribute($maxMarks = 100)
    {
        return ($this->marks / $maxMarks) * 100;
    }
}
