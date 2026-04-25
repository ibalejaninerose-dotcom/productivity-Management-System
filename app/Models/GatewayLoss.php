<?php

namespace App\Http\Controllers;

use App\Models\GatewayLoss;
use Illuminate\Http\Request;

class GatewayLossController extends Controller
{
    public function index(Request $request)
    {
        $query = GatewayLoss::query();
        
        if ($request->has('provider')) {
            $query->where('provider', $request->provider);
        }
        
        $query->orderBy('created_at', 'desc');
        $gatewayLosses = $query->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $gatewayLosses,
            'message' => 'Gateway losses retrieved successfully'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider' => 'required|string|max:50',
            'method' => 'sometimes|string|max:10',
            'response_status' => 'nullable|integer',
            'content_payload' => 'nullable|array',
            'exec_corusage' => 'nullable|string'
        ]);
        
        $gatewayLoss = GatewayLoss::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $gatewayLoss,
            'message' => 'Gateway loss created successfully'
        ], 201);
    }

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
            'data' => $gatewayLoss,
            'message' => 'Gateway loss retrieved successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $gatewayLoss = GatewayLoss::find($id);
        
        if (!$gatewayLoss) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway loss not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'provider' => 'sometimes|string|max:50',
            'method' => 'sometimes|string|max:10',
            'response_status' => 'nullable|integer',
            'content_payload' => 'nullable|array',
            'exec_corusage' => 'nullable|string'
        ]);
        
        $gatewayLoss->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $gatewayLoss,
            'message' => 'Gateway loss updated successfully'
        ]);
    }

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
        ]);
    }
    
    public function statistics()
    {
        $stats = [
            'total' => GatewayLoss::count(),
            'by_provider' => GatewayLoss::selectRaw('provider, count(*) as total')
                ->groupBy('provider')
                ->get(),
            'by_status' => GatewayLoss::selectRaw('response_status, count(*) as total')
                ->groupBy('response_status')
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistics retrieved successfully'
        ]);
    }
}