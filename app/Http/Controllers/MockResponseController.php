<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MockResponseController extends Controller
{
    public function create(Request $request)
    {
        $status = $request->header('X-Mock-Status');

        if ($status === 'accepted') {
            return response()->json(['message' => 'Mock response for successful transaction'], 200);
        } elseif ($status === 'failed') {
            return response()->json(['message' => 'Mock response for failed transaction'], 400);
        } else {
            return response()->json(['error' => 'Invalid X-Mock-Status header'], 400);
        }
    }
}
