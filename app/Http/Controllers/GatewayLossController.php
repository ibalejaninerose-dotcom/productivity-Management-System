<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GatewayLossController extends Controller
{
    // GET /api/gateway-loss
    public function index()
    {
        $records = DB::table('gateway_loss')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'data' => $records
            ],
            'message' => 'Gateway losses retrieved successfully'
        ]);
    }

    // POST /api/gateway-loss
    public function store(Request $request)
    {
        $id = DB::table('gateway_loss')->insertGetId([
            'provider' => $request->provider,
            'method' => $request->method ?? 'GET',
            'response_status' => $request->response_status,
            'content_payload' => json_encode($request->content_payload),
            'exec_corusage' => $request->exec_corusage,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $record = DB::table('gateway_loss')->where('id', $id)->first();
        
        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Gateway loss created successfully'
        ], 201);
    }

    // GET /api/gateway-loss/{id}
    public function show($id)
    {
        $record = DB::table('gateway_loss')->where('id', $id)->first();
        
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway loss not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Gateway loss retrieved successfully'
        ]);
    }

    // PUT /api/gateway-loss/{id}
    public function update(Request $request, $id)
    {
        $record = DB::table('gateway_loss')->where('id', $id)->first();
        
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway loss not found'
            ], 404);
        }
        
        DB::table('gateway_loss')->where('id', $id)->update([
            'provider' => $request->provider ?? $record->provider,
            'method' => $request->method ?? $record->method,
            'response_status' => $request->response_status ?? $record->response_status,
            'content_payload' => $request->content_payload ? json_encode($request->content_payload) : $record->content_payload,
            'exec_corusage' => $request->exec_corusage ?? $record->exec_corusage,
            'updated_at' => now()
        ]);
        
        $updated = DB::table('gateway_loss')->where('id', $id)->first();
        
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Gateway loss updated successfully'
        ]);
    }

    // DELETE /api/gateway-loss/{id}
    public function destroy($id)
    {
        $record = DB::table('gateway_loss')->where('id', $id)->first();
        
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway loss not found'
            ], 404);
        }
        
        DB::table('gateway_loss')->where('id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Gateway loss deleted successfully'
        ]);
    }

    // GET /api/gateway-loss-statistics
    public function statistics()
    {
        $total = DB::table('gateway_loss')->count();
        
        $byProvider = DB::table('gateway_loss')
            ->select('provider', DB::raw('count(*) as total'))
            ->groupBy('provider')
            ->get();
        
        // Status distribution: Success (2xx) vs Failed (4xx,5xx)
        $success = DB::table('gateway_loss')
            ->whereBetween('response_status', [200, 299])
            ->count();
        
        $clientError = DB::table('gateway_loss')
            ->whereBetween('response_status', [400, 499])
            ->count();
            
        $serverError = DB::table('gateway_loss')
            ->whereBetween('response_status', [500, 599])
            ->count();
        
        // Daily trend (last 7 days)
        $dailyTrend = DB::table('gateway_loss')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'by_provider' => $byProvider,
                'status_distribution' => [
                    'success' => $success,
                    'client_error' => $clientError,
                    'server_error' => $serverError
                ],
                'daily_trend' => $dailyTrend
            ],
            'message' => 'Statistics retrieved successfully'
        ]);
    }
}