<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ParentDetail extends Model
{
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'parent_id_no'; // Specify the primary key
    protected $fillable = [
        'parent_id_no',
        'parent_first_name',
        'parent_middle_name',
        'parent_last_name',
        'parent_phone_number',
        'parent_email',
        'parent_password',
    ];

    public function student_records()
    {
        // Corrected relationship to reference the foreign key
        return $this->hasMany(StudentRecord::class, 'parent_id_no', 'parent_id_no');
    }
}


