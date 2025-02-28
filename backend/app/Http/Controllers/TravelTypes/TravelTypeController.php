<?php

namespace App\Http\Controllers\TravelTypes;

use App\Http\Controllers\Controller;
use App\Models\TravelType;
use Illuminate\Http\JsonResponse;

class TravelTypeController extends Controller
{
    public function list(): JsonResponse
    {
        $travelTypes = TravelType::all(['id', 'name']);
        return response()->json(['success' => true, 'data' => $travelTypes], 200);
    }
}
