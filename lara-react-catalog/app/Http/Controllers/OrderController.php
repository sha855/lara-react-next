<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

    public function metrics(Request $request)
{
    $validator = Validator::make($request->all(), [
        'restaurant_id' => 'nullable|exists:restaurants,id',
        'start_date'    => 'required|date',
        'end_date'      => 'required|date|after_or_equal:start_date',
        'min_amount'    => 'nullable|numeric',
        'max_amount'    => 'nullable|numeric',
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'error'   => 'Validation failed',
            'details' => $validator->errors()
        ], 422);
    }
    $validated = $validator->validated();
    $restaurantId = $validated['restaurant_id'] ?? null;
    $startDate = $validated['start_date'];
    $endDate = $validated['end_date'];

    try {
        $query = Order::query();

        if ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        }

        $query->whereBetween('order_time', [$startDate, $endDate]);

        if (!empty($validated['min_amount'])) {
            $query->where('order_amount', '>=', $validated['min_amount']);
        }

        if (!empty($validated['max_amount'])) {
            $query->where('order_amount', '<=', $validated['max_amount']);
        }

        $orders = $query->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No orders found for the given criteria.',
                'data'    => []
            ], 200);
        }

        $grouped = $orders->groupBy(fn($item) => substr($item->order_time, 0, 10));

        $metrics = $grouped->map(function($orders, $date) {
            $hourly = $orders->groupBy(fn($item) => (int) substr($item->order_time, 11, 2));
            $peakHour = $hourly->sortByDesc(fn($h) => count($h))->keys()->first();

            return [
                'date'            => $date,
                'orders_count'    => $orders->count(),
                'revenue'         => $orders->sum('order_amount'),
                'avg_order_value' => round($orders->avg('order_amount'), 2),
                'peak_hour'       => $peakHour ?? null,
            ];
        })->values();

        return response()->json([
            'message' => 'Metrics fetched successfully.',
            'data'    => $metrics
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error'   => 'Something went wrong',
            'message' => $e->getMessage()
        ], 500);
    }
}

    
}