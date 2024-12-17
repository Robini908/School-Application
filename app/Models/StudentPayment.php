<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(StudentRecord::class);
    }
}

