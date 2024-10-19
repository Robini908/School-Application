<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRecord extends Model
{
    use HasFactory;

    // Ensure that the 'id' column is the primary key
    protected $primaryKey = 'id';

    // If the primary key is not an incrementing integer
    public $incrementing = true;
    protected $keyType = 'int';

    // Fillable fields
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
        'is_suspended',              // Renamed from is_expelled
        'suspension_reason',          // Renamed from expulsion_reason
        'suspended_by',               // Renamed from expelled_by
        'notification_content',
        'suspension_date',            // Renamed from expulsion_date
        'suspension_type',            // Renamed from expulsion_type
        'suspension_end_date',        // Renamed from expulsion_end_date
        'disapproval_reason'
    ];

    // Casts to ensure proper handling of date fields
    protected $casts = [
        'suspension_date' => 'datetime',         // Renamed from expulsion_date
        'suspension_end_date' => 'datetime',     // Renamed from expulsion_end_date
        // Add other date fields here if needed
    ];

    public function parent_detail()
    {
        return $this->belongsTo(ParentDetail::class, 'parent_id_no', 'parent_id_no');
    }
    

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function promotionsDemotions()
    {
        return $this->hasMany(StudentPromotionDemotion::class);
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

    public function examMarks()
    {
        return $this->hasMany(ExamMarks::class, 'student_id'); 
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

