<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentRecord extends Model
{
    use HasFactory; 

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'parent_id_no',
        'my_class_id',
        'section_id',
        'adm_no',
        'dorm_id',
        'year_admitted',
        'kcpe',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'gender',
        'phone',
        'dob',
        'nal_id',
        'state_id',
        'lga_id',
        'town',
        'bg_id',
        'photo',
        'status',
        'student_password',
        'is_suspended',
        'suspension_reason',
        'suspended_by',
        'notification_content',
        'suspension_date',
        'suspension_type',
        'suspension_end_date',
        'disapproval_reason',
    ];

    protected $casts = [
        'suspension_date' => 'datetime',
        'suspension_end_date' => 'datetime',
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id')
            ->withTimestamps();
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(StudentTransition::class, 'student_id', 'id');
    }

    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMarks::class, 'student_id');
    }




    public function parent_detail()
    {
        return $this->belongsTo(ParentDetail::class, 'parent_id_no', 'parent_id_no');
    }





    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }



    public function getCurrentClass($academicYear)
    {
        $latestTransition = $this->transitions()
            ->where('academic_year', $academicYear)
            ->latest('event_date')
            ->first();

        return $latestTransition ? $latestTransition->newClass : null;
    }

    /**
     * Get the section that this student belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function dorm()
    {
        return $this->belongsTo(Dorm::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(ExamMarks::class, 'student_id');
    }

    public function studentResults(): HasMany
    {
        return $this->hasMany(StudentResult::class, 'student_id', 'id');
    }
}
