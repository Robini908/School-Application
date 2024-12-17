<?php

namespace App\Http\Controllers;

use App\Services\MpesaService;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    public function callback(Request $request, MpesaService $mpesaService)
    {
        // Here you can pass the callback data to handle it
        $response = $mpesaService->handleCallback($request->all());

        // Respond to the callback request (if necessary)
        return response()->json($response);
    }
}
