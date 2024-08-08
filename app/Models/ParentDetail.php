<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentRecord;

class ParentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id_no',
        'parent_first_name',
        'parent_middle_name',
        'parent_last_name',
        'parent_phone_number',
        'parent_email',
        'parent_password',
    ];

    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }
}
