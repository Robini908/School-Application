<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'read',
        'type',
        'attachment',
        'read_at',
        'delivered_at'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
