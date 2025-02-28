<?php

namespace App\Services\TravelOrders;

use App\Repositories\TravelOrders\TravelOrdersRepositoryInterface;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class TravelOrdersService
{
    private $travelOrdersRepository;
    private $notificationService;

    public function __construct(
        TravelOrdersRepositoryInterface $travelOrdersRepository,
        NotificationService $notificationService
    ) {
        $this->travelOrdersRepository = $travelOrdersRepository;
        $this->notificationService = $notificationService;
    }

    public function create($data): array
    {
        try {
            $data['user_id'] = Auth::id();
            $order = $this->travelOrdersRepository->create($data);

            return [
                'message' => 'travel_order_created_successfully',
                'status' => 201,
                'order' => $order
            ];
        } catch (Exception $e) {
            Log::error('error_creating_travel_order: ' . $e->getMessage());
            throw new Exception('unable_to_create_travel_order');
        }
    }

    public function update(array $data, string $id): array
    {
        try {
            $order = $this->travelOrdersRepository->update($id, $data);

            return [
                'message' => 'travel_order_updated_successfully',
                'status' => 200,
                'order' => $order
            ];
        } catch (Exception $e) {
            Log::error('error_updating_travel_order: ' . $e->getMessage());
            throw new Exception('unable_to_update_travel_order');
        }
    }

    public function delete($request): array
    {
        try {
            $this->travelOrdersRepository->delete($request['id']);
            return [
                'message' => 'travel_order_deleted',
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error('error_to_delete_travel_order ' . $e->getMessage());
            throw new Exception('the_travel_request_could_not_be_deleted');
        }
    }

    public function listAll(array $filters): array
    {
        try {
            $userType = Auth::user()->user_type;
            $userId = Auth::id();
            return $this->travelOrdersRepository->listAll($filters, $userType, $userId)->toArray();
        } catch (\Throwable $th) {
            Log::error('error_listing_TravelOrders: ' . $th->getMessage());
            throw new Exception('unable_to_list_TravelOrders');
        }
    }

    public function updateStatus(string $id, array $data): array
    {
        try {    
            $order = $this->travelOrdersRepository->update($id, $data); 
    
            $order->load(['destination', 'status']);
    
            if ($order->status->name === 'approved' || $order->status->name === 'canceled') {
                $formattedDate = Carbon::parse($order->departure_date)->format('d/m/Y');
                $message = "Your order: {$order->id}, to {$order->destination->name} on {$formattedDate} has been {$order->status->name}.";
                $this->notificationService->create($order->user_id, $message);
            }
    
            return [
                'message' => "status_updated_successfully",
                'status' => 200,
                'order' => $order
            ];
        } catch (\Throwable $th) {
            Log::error('error_updating_travel_order_status: ' . $th->getMessage());
            throw new Exception('unable_to_update_travel_order_status');
        }
    }
    

    public function getStatistics(): array
    {
        try {
            $userType = Auth::user()->user_type;
            $userId = Auth::id();
            return $this->travelOrdersRepository->getTravelOrderStatistics($userType, $userId);
        } catch (\Throwable $th) {
            Log::error('error_getting_travel_order_statistics: ' . $th->getMessage());
            throw new Exception('unable_to_get_travel_order_statistics');
        }
    }
}
