<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'session',
        'user_id',
        'my_class_id',
        'section_id',
        'my_parent_id',
        'dorm_id',
        'dorm_room_no',
        'adm_no',
        'year_admitted',
        'wd',
        'wd_date',
        'grad',
        'grad_date',
        'house',
        'age',
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
        'bg_id',
        'photo',
        'parent_first_name',
        'parent_middle_name',
        'parent_last_name',
        'nin',
        'parent_phone',
        'parent_email',
        'password',
        'status',
        'disapproval_reason',
        'disapproval_description',
    ];

   /* public function user()
    {
        return $this->belongsTo(UserType::class);
    } */

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
}
