<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentTransition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'transition_year',
        'transition_type',
        'target_class_id',
        'target_section_id',
        'reason',
        'decision_by',
        'decision_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'decision_date' => 'datetime',
    ];

    /**
     * Get the student associated with this transition.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }

    /**
     * Get the target class for this transition.
     */
    public function targetClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'target_class_id');
    }

    /**
     * Get the target section for this transition.
     */
    public function targetSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'target_section_id');
    }

    /**
     * Get the user who made the decision.
     */
    public function decisionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decision_by');
    }
}