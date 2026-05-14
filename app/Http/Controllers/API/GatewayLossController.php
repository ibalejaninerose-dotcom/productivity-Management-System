<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GatewayLoss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GatewayLossController extends Controller
{
    // GET all records
    public function index()
    {
        $gatewayLosses = GatewayLoss::all();
        
        return response()->json([
            'success' => true,
            'message' => 'Gateway losses retrieved successfully',
            'data' => $gatewayLosses,
            'count' => $gatewayLosses->count()
        ], 200);
    }

    // POST create record
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway_name' => 'required|string|max:100',
            'loss_percentage' => 'required|numeric|min:0|max:100',
            'incident_date' => 'required|date',
            'status' => 'required|in:open,investigating,resolved'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $gatewayLoss = GatewayLoss::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Gateway loss created successfully',
            'data' => $gatewayLoss
        ], 201);
    }

    // GET single record
    public function show($id)
    {
        $gatewayLoss = GatewayLoss::find($id);
        
        if (!$gatewayLoss) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway loss not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Gateway loss retrieved successfully',
            'data' => $gatewayLoss
        ], 200);
    }

    // PUT update record
    public function update(Request $request, $id)
    {
        $gatewayLoss = GatewayLoss::find($id);
        
        if (!$gatewayLoss) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway loss not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'gateway_name' => 'sometimes|string|max:100',
            'loss_percentage' => 'sometimes|numeric|min:0|max:100',
            'status' => 'sometimes|in:open,investigating,resolved'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $gatewayLoss->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Gateway loss updated successfully',
            'data' => $gatewayLoss
        ], 200);
    }

    // DELETE record
    public function destroy($id)
    {
        $gatewayLoss = GatewayLoss::find($id);
        
        if (!$gatewayLoss) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway loss not found'
            ], 404);
        }
        
        $gatewayLoss->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Gateway loss deleted successfully'
        ], 200);
    }

    // GET statistics
    public function statistics()
    {
        $total = GatewayLoss::count();
        $open = GatewayLoss::where('status', 'open')->count();
        $investigating = GatewayLoss::where('status', 'investigating')->count();
        $resolved = GatewayLoss::where('status', 'resolved')->count();
        $avgLoss = GatewayLoss::avg('loss_percentage');
        
        return response()->json([
            'success' => true,
            'message' => 'Statistics retrieved successfully',
            'data' => [
                'total_incidents' => $total,
                'open_incidents' => $open,
                'investigating_incidents' => $investigating,
                'resolved_incidents' => $resolved,
                'resolution_rate' => $total > 0 ? round(($resolved / $total) * 100, 2) : 0,
                'average_loss_percentage' => round($avgLoss ?? 0, 2)
            ]
        ], 200);
    }
}