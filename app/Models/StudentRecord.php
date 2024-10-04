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
        'is_expelled',
        'expulsion_reason',
        'expelled_by',
        'notification_content', // Added missing field
        'expulsion_date',
        'expulsion_type',
        'expulsion_end_date',
        'disapproval_reason' // Added missing field
    ];
    

    public function parent_detail()
    {
        // Corrected relationship to reference the foreign key
        return $this->belongsTo(ParentDetail::class, 'parent_id_no', 'parent_id_no');
    }
    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

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
        // Ensure the foreign key is correctly defined
        return $this->hasMany(ExamMarks::class, 'student_id'); // student_id should be the foreign key
    }

    public function marks(): HasMany
    {
        return $this->hasMany(ExamMarks::class, 'student_id');
    }

    // Check if the student is expelled

    
}
