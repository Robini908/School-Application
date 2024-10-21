<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentTransition extends Model
{
    use HasFactory;

    // Set the primary key to 'id' and specify that it is not an auto-incrementing integer
    protected $primaryKey = 'id';
    public $incrementing = false; // UUIDs are not auto-incrementing
    protected $keyType = 'string'; // Since we are using UUIDs

    // Fillable fields
    protected $fillable = [
        'transition_uid',
        'student_id',
        'new_class_id',
        'new_section_id',
        'transition_type',
        'reason',
        'event_date',
        'repetition_count',
        'academic_year',
        'next_academic_year',
        'completion_status',
        'approved_by',
        'approved_at',
        'remarks',
    ];

    /**
     * Define the relationship with StudentRecord.
     * Each transition is associated with a student.
     */
    public function studentRecord(): BelongsTo
    {
        return $this->belongsTo(StudentRecord::class, 'student_id', 'id');
    }

    /**
     * Define the relationship with MyClass.
     * Each transition has a new class associated with it.
     */
    public function newClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'new_class_id', 'id');
    }

    /**
     * Define the relationship with Section.
     * Each transition has a new section associated with it.
     */
    public function newSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'new_section_id', 'id');
    }

    /**
     * Define the relationship with User.
     * Each transition can be approved by a user.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
