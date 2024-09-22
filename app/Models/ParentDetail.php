<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDetail extends Model
{
    use HasFactory;

    // Specify the primary key is not an incrementing integer, but a string
    protected $primaryKey = 'parent_id_no';
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable attributes
    protected $fillable = [
        'parent_id_no',
        'parent_first_name',
        'parent_middle_name',
        'parent_last_name',
        'parent_phone_number',
        'parent_email',
        'parent_password',
    ];

    /**
     * Define the relationship with the StudentRecord model
     * A parent can have many student records
     */
    public function student_records()
    {
        return $this->hasMany(StudentRecord::class, 'parent_id_no', 'parent_id_no');
    }
}
