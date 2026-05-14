<?php

namespace App\Http\Controllers;

use App\Models\GatewayLoss;
use Illuminate\Http\Request;

class GatewayLossController extends Controller
{
    // GET all records
    public function index()
    {
        $records = GatewayLoss::all();
        return response()->json([
            'success' => true,
            'data' => $records,
            'message' => 'Records retrieved successfully'
        ], 200);
    }

    // GET single record
    public function show($id)
    {
        $record = GatewayLoss::find($id);
        
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Record retrieved successfully'
        ], 200);
    }

    // POST create record
    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'loss_amount' => 'required|numeric',
            'date' => 'required|date'
        ]);

        $record = GatewayLoss::create([
            'provider' => $request->provider,
            'loss_amount' => $request->loss_amount,
            'date' => $request->date,
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Record created successfully'
        ], 201);
    }

    // PUT update record (full update)
    public function update(Request $request, $id)
    {
        $record = GatewayLoss::find($id);
        
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $request->validate([
            'provider' => 'required|string',
            'loss_amount' => 'required|numeric',
            'date' => 'required|date'
        ]);
        
        $record->update([
            'provider' => $request->provider,
            'loss_amount' => $request->loss_amount,
            'date' => $request->date,
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Record updated successfully'
        ], 200);
    }

    // PATCH update record (partial update)
    public function partialUpdate(Request $request, $id)
    {
        $record = GatewayLoss::find($id);
        
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }

        // I-update lang ang mga fields nga naa sa request
        if ($request->has('provider')) {
            $record->provider = $request->provider;
        }
        if ($request->has('loss_amount')) {
            $record->loss_amount = $request->loss_amount;
        }
        if ($request->has('date')) {
            $record->date = $request->date;
        }
        
        $record->save();
        
        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Record partially updated successfully'
        ], 200);
    }

    // DELETE record
    public function destroy($id)
    {
        $record = GatewayLoss::find($id);
        
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }
        
        $record->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ], 200);
    }
}