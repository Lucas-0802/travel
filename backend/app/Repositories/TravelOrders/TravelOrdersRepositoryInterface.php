<?php

namespace App\Repositories\TravelOrders;

use App\Models\TravelOrder;
use Illuminate\Support\Collection;

interface TravelOrdersRepositoryInterface
{
    public function create(array $data): TravelOrder;
    public function findById(string $id): ?TravelOrder;
    public function update(string $id, array $data): TravelOrder;
    public function delete(string $id): bool;
    public function listAll(array $filters, string $userType, string $userId ): Collection;
    public function updateStatus(string $id, int $status): TravelOrder;
    public function getTravelOrderStatistics(string $userType, string $userId): array;
}
