<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Qs; // Assuming Qs is your helper class

class SessionController extends Controller
{
    public function getSession()
    {
        try {
            $currentSession = Qs::getCurrentSession();

            if ($currentSession) {
                return response()->json(['session' => $currentSession]);
            } else {
                return response()->json(['error' => 'Current session not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch session: ' . $e->getMessage()], 500);
        }
    }
}
