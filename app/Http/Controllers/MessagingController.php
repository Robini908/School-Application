<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class MessagingController extends Controller
{
    /**
     * Display a listing of the messages.
     */
    public function index()
    {
        // Get all messages for the authenticated user
        $messages = ChatMessage::where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Return view with messages
        return view('messages.index', compact('messages'));
    }
}
