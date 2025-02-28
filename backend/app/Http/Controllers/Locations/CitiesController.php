<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use Illuminate\Http\JsonResponse;

class CitiesController extends Controller
{
    public function list(): JsonResponse
    {
        $cities = Cities::all(['id', 'name']);
        return response()->json(['success' => true, 'data' => $cities], 200);
    }
}
