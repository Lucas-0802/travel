<?php

namespace App\Repositories\TravelOrders;

use App\Models\TravelOrder;
use Illuminate\Support\Collection;


class TravelOrdersRepository implements TravelOrdersRepositoryInterface
{
    public function create(array $data): TravelOrder
    {
        return TravelOrder::create($data);
    }

    public function findById(string $id): ?TravelOrder
    {
        return TravelOrder::find($id);
    }

    public function update(string $id, array $data): TravelOrder
    {
        $travelOrder = TravelOrder::findOrFail($id);
        $travelOrder->update($data);
        return $travelOrder;
    }

    public function delete(string $id): bool
    {
        return TravelOrder::where('id', $id)->delete() > 0;
    }

    public function listAll(array $filters, string $userType, string $userId): Collection
    {
        $query = TravelOrder::query()->with(['user', 'status', 'origin', 'destination', 'travelType']);

        if ($userType !== 'admin') {
            $query->where('user_id', $userId);
        }
        
        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        if (!empty($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        if (!empty($filters['origin_id'])) {
            $query->where('origin_id', 'LIKE', "%{$filters['origin_id']}%");
        }

        if (!empty($filters['destination_id'])) {
            $query->where('destination_id', 'LIKE', "%{$filters['destination_id']}%");
        }

        if (!empty($filters['travel_type_id'])) {
            $query->where('status_id', $filters['travel_type_id']);
        }

        if (!empty($filters['departure_date'])) {
            $query->whereDate('departure_date', $filters['departure_date']);
        }

        if (!empty($filters['return_date'])) {
            $query->whereDate('return_date', $filters['return_date']);
        }

        return $query->orderBy('travel_orders.created_at', 'desc')->get();
    }

    public function updateStatus(string $id, int $status): TravelOrder
    {
        $travelOrder = TravelOrder::findOrFail($id);
        $travelOrder->update(['status_id' => $status]);

        return $travelOrder;
    }

    public function getTravelOrderStatistics(string $userType, string $userId = null): array
    {
        $query = TravelOrder::query();

        if ($userType === 'common' && $userId) {
            $query->where('user_id', $userId);
        }

        $totalTrips = $query->count();

        $totals = $query->selectRaw('status_id, COUNT(*) as total')
            ->whereIn('status_id', [1, 2, 3])
            ->groupBy('status_id')
            ->pluck('total', 'status_id')
            ->toArray();

        return [
            'total_trips' => $totalTrips,
            'status_counts' => $totals
        ];
    }
}
