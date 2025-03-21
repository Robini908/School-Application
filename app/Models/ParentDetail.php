<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ParentDetail extends Model
{
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'parent_id_no'; // Specify the primary key
    public $incrementing = false; // Since parent_id_no is not an auto-increment field
    protected $keyType = 'string'; // Specify the key type

    protected $fillable = [
        'parent_id_no',
        'user_id',
        'parent_first_name',
        'parent_middle_name',
        'parent_last_name',
        'parent_phone_number',
        'parent_email',
        'parent_password',
    ];

    // public function getAuthPassword()
    // {
    //     return $this->parent_password;
    // }

    public function student_records()
    {
        // Reference to StudentRecord with correct foreign key relationship
        return $this->hasMany(StudentRecord::class, 'parent_id_no', 'parent_id_no');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
