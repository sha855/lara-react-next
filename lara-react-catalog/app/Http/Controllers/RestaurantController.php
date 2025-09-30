<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable|string|max:255',
                'page'   => 'nullable|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error'   => 'Validation failed',
                    'details' => $validator->errors()
                ], 422);
            }

            $query = Restaurant::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            $restaurants = $query->paginate(10);

            return response()->json([
                'message' => 'Restaurants fetched successfully',
                'data'    => $restaurants
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function topRestaurants(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error'   => 'Validation failed',
                    'details' => $validator->errors()
                ], 422);
            }

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $cacheKey = "top_restaurants_{$startDate}_{$endDate}";
            $data = Cache::remember($cacheKey, now()->addMinutes(5), function() use ($startDate, $endDate) {
                $restaurants = Restaurant::with(['orders' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('order_time', [$startDate, $endDate]);
                }])->get();

                return $restaurants->map(function($r) {
                    return [
                        'id' => $r->id,
                        'name' => $r->name,
                        'revenue' => $r->orders->sum('order_amount')
                    ];
                })->sortByDesc('revenue')->take(3)->values();
            });

            return response()->json([
                'message' => 'Top restaurants fetched successfully',
                'data'    => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
