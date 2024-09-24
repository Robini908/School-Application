<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;
    // Ensure that the 'id' column is the primary key
    protected $primaryKey = 'id';

    // If the primary key is not an incrementing integer
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id',
        'parent_id',
        'my_class_id',
        'section_id',
        'dorm_id',
        'adm_no',

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
        'town',
        'bg_id',
        'photo',
        'status',
        'student_password'
    ];

    public function parent_detail()
    {
        return $this->belongsTo(ParentDetail::class, 'parent_id_no', 'parent_id_no');
    }

    /* public function my_parent()
    {
        return $this->belongsTo(UserType::class, 'my_parent_id');
    } */

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
        return $this->hasMany(ExamMarks::class);
    }

    
}
