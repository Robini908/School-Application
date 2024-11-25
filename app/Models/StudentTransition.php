<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class StudentTransition extends Model
{
    use HasFactory;

    // Set the primary key to 'id' and specify that it is not an auto-incrementing integer
    protected $primaryKey = 'id';
    public $incrementing = false; // UUIDs are not auto-incrementing
    protected $keyType = 'string'; // Since we are using UUIDs

    // Fillable fields
    protected $fillable = [
        'id',                // Manually filled
        'transition_uid',    // Add this line
        'student_id',
        'new_class_id',
        'new_section_id',
        'transition_type',
        'reason',
        'event_date',
        'academic_year',
        'next_academic_year',
        'approved_by',
        'remarks',
    ];

    // Automatically generate UUID for the 'id' field on creation
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->transition_uid = (string) Str::uuid(); // Generate transition_uid here
        });
    }

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

    public function scopeCurrentYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear)
            ->whereNull('next_academic_year'); // Ensures they haven't been promoted yet
    }
}
