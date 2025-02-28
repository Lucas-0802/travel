<?php

namespace App\Http\Controllers\TravelOrders;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelOrders\CreateOrderRequest;
use App\Http\Requests\TravelOrders\DeleteOrderRequest;
use App\Http\Requests\TravelOrders\OrderRequest;
use App\Http\Requests\TravelOrders\TravelOrderRequest;
use App\Http\Requests\TravelOrders\UpdateOrderStatusRequest;
use App\Services\TravelOrders\TravelOrdersService;
use Illuminate\Http\JsonResponse;

class TravelOrdersController extends Controller
{
    private $travelOrderService;

    public function __construct(TravelOrdersService $travelOrderService)
    {
        $this->travelOrderService = $travelOrderService;
    }

    public function create(CreateOrderRequest $request): JsonResponse
    {
        $response = $this->travelOrderService->create($request->all());
        return response()->json($response, $response['status']);
    }
    
    public function list(TravelOrderRequest $request): JsonResponse
    {
        $filters = $request->filters();
        $orders = $this->travelOrderService->listAll($filters);

        return response()->json(['orders' => $orders], 200);
    }


    public function update(OrderRequest $request, string $id): JsonResponse
    {
        $response = $this->travelOrderService->update($request->all(), $id);
        return response()->json($response, $response['status']);
    }

    public function delete(DeleteOrderRequest $request): JsonResponse
    {
        $response = $this->travelOrderService->delete($request->all());
        return response()->json($response, $response['status']);
    }

    public function getStatistics(): JsonResponse
    {
        $response = $this->travelOrderService->getStatistics();
        return response()->json(['statistics' => $response]);
    }

    public function updateStatus(string $id, UpdateOrderStatusRequest $request): JsonResponse
    {
        $response = $this->travelOrderService->updateStatus($id, $request->all());
        return response()->json($response, $response['status']);
    }
}
