<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Dorm extends Model
{

    use BelongsToTenant;
    protected $fillable = ['name', 'capacity', 'description'];

    // Relationship to the User model (teacher) via the pivot table
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'dorm_teacher', 'dorm_id', 'user_id')
            ->withPivot('session') // Include the session from the pivot table
            ->withTimestamps();    // Include timestamps if needed
    }

    // Relationship to the StudentRecord model
    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class);
    }

    public function students()
    {
        return $this->belongsToMany(StudentRecord::class, 'dorm_student', 'dorm_id', 'student_id')
                    ->withPivot('year') // Include the year in the pivot table
                    ->withTimestamps();
    }

    /**
     * Check if the dorm has reached its capacity.
     *
     * @return bool
     */
    public function isFull()
    {
        return $this->studentRecords()->count() >= $this->capacity;
    }

    /**
     * Get the dorm master associated with this dorm.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dorm_master()
    {
        return $this->belongsTo(User::class, 'dorm_master_id');
    }
}
