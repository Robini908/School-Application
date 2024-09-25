<?php

namespace App;

use App\Models\Lga;
use App\Models\Dorm;
use App\Models\State;
use App\Models\UserType;
use App\Models\BloodGroup;
use App\Models\DormMaster;
use App\Models\Nationality;
use App\Models\StaffRecord;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Section; // Import Section model
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'phone', 'phone2', 'dob', 'gender', 'photo', 'address', 'bg_id', 'password', 'nal_id', 'state_id', 'lga_id', 'code', 'user_type', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Define relationships
    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nal_id');
    }

    public function blood_group()
    {
        return $this->belongsTo(BloodGroup::class, 'bg_id');
    }

    public function staff()
    {
        return $this->hasMany(StaffRecord::class);
    }

    // Relationship to MyClass as the master (teacher)
    public function classes_as_master()
    {
        return $this->hasMany(MyClass::class, 'master_id')->where('user_type', 'teacher');
    }

    /**
     * Define the relationship with Section.
     * Each user (teacher) can be associated with multiple sections.
     */
    public function sections()
    {
        return $this->hasMany(Section::class, 'teacher_id'); // Assuming 'teacher_id' is the foreign key in the sections table
    }
}
