<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamClassSection extends Model
{
    protected $table = 'exam_class_section';

    protected $fillable = ['exam_id', 'class_id', 'section_id'];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
