<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiGatewayLossController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Gateway losses retrieved successfully'
        ], 200);
    }
}