<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GatewayLossRecordController extends Controller
{
    // Get all records
    public function index(Request $request)
    {
        $query = DB::table('gateway_logs');
        
        // Apply filter if provider is specified
        if ($request->has('provider') && $request->provider) {
            $query->where('provider', $request->provider);
        }
        
        // Get records ordered by newest first
        $records = $query->orderBy('id', 'desc')->get();
        
        // Decode JSON fields for response
        foreach ($records as $record) {
            if ($record->request_payload) {
                $record->request_payload = json_decode($record->request_payload, true);
            }
        }
        
        return response()->json($records);
    }
    
    // Get single record by ID
    public function show($id)
    {
        $record = DB::table('gateway_logs')->where('id', $id)->first();
        
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        
        if ($record->request_payload) {
            $record->request_payload = json_decode($record->request_payload, true);
        }
        
        return response()->json($record);
    }
    
    // Create new record
    public function store(Request $request)
    {
        // Validate required fields
        if (!$request->provider) {
            return response()->json(['error' => 'Provider is required'], 422);
        }
        if (!$request->endpoint) {
            return response()->json(['error' => 'Endpoint is required'], 422);
        }
        
        // Handle request_payload - convert array to JSON string if needed
        $requestPayload = $request->request_payload;
        if (is_array($requestPayload)) {
            $requestPayload = json_encode($requestPayload);
        }
        
        // Insert into database
        $id = DB::table('gateway_logs')->insertGetId([
            'provider' => $request->provider,
            'endpoint' => $request->endpoint,
            'response_status' => $request->response_status ?? null,
            'request_payload' => $requestPayload,
            'error_message' => $request->error_message ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true, 
            'id' => $id, 
            'message' => 'Record added successfully'
        ], 201);
    }
    
    // Update existing record
    public function update(Request $request, $id)
    {
        // Check if record exists
        $existing = DB::table('gateway_logs')->where('id', $id)->first();
        if (!$existing) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        
        // Handle request_payload
        $requestPayload = $request->request_payload;
        if (is_array($requestPayload)) {
            $requestPayload = json_encode($requestPayload);
        }
        
        // Update the record
        DB::table('gateway_logs')
            ->where('id', $id)
            ->update([
                'provider' => $request->provider ?? $existing->provider,
                'endpoint' => $request->endpoint ?? $existing->endpoint,
                'response_status' => $request->response_status ?? $existing->response_status,
                'request_payload' => $requestPayload ?? $existing->request_payload,
                'error_message' => $request->error_message ?? null,
                'updated_at' => now()
            ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Record updated successfully'
        ]);
    }
    
    // Delete record
    public function destroy($id)
    {
        // Check if record exists
        $existing = DB::table('gateway_logs')->where('id', $id)->first();
        if (!$existing) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        
        // Delete the record
        DB::table('gateway_logs')->where('id', $id)->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Record deleted successfully'
        ]);
    }
}