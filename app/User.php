<?php

namespace App;

use App\Models\{
    Lga,
    Dorm,
    State,
    MyClass,
    UserType,
    ChatMessage,
    BloodGroup,
    Nationality,
    StaffRecord,
    StudentRecord,
    ParentDetail,
    StudentTransition,
    StudentPromotionDemotion,
    Section,
    Tenant
};
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;


class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'phone2',
        'dob',
        'gender',
        'photo',
        'address',
        'bg_id',
        'password',
        'nal_id',
        'state_id',
        'lga_id',
        'code',
        'user_type',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define relationships
    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function dorms()
    {
        return $this->belongsToMany(Dorm::class, 'dorm_teacher', 'user_id', 'dorm_id')
            ->withPivot('session') // Include the session from the pivot table
            ->withTimestamps();    // Include timestamps if needed
    }

    public function parentDetails()
    {
        return $this->hasOne(ParentDetail::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(MyClass::class, 'class_teacher', 'user_id', 'my_class_id')
            ->withPivot('session') // Include the session column from the pivot table
            ->withTimestamps();    // Include timestamps if needed
    }

    public function studentRecord()
    {
        return $this->hasOne(StudentRecord::class);
    }



    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type');
    }
    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
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

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'sender_id')
            ->orWhere('receiver_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->withDefault(); // Ensure it returns a default model if no message exists
    }
}
