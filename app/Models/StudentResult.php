<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    use HasFactory;

    protected $table = 'student_results';

    // Specify which attributes can be mass assigned
    protected $fillable = [
        'student_id',
        'exam_id', // Add exam_id for mass assignment
        'total_marks',
        'total_points',
        'mean_score',
        'mean_grade',
        'position',
        'stream_position',
    ];

    

    // Define the relationship with StudentRecord
    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id', 'id'); // Adjust 'id' if your primary key is different
    }

    // Define the relationship with Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id'); // Adjust 'id' if your primary key is different
    }
}
