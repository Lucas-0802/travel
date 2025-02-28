<?php

namespace App\Http\Controllers\TravelOrders;

use App\Http\Controllers\Controller;
use App\Models\TravelOrderStatus;
use Illuminate\Http\JsonResponse;

class TravelOrderStatusController extends Controller
{
    public function list(): JsonResponse
    {
        $status = TravelOrderStatus::all(['id', 'name']);
        return response()->json(['success' => true, 'data' => $status], 200);
    }
}
