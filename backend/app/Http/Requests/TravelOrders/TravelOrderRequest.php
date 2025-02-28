<?php

namespace App\Http\Requests\TravelOrders;

use Illuminate\Foundation\Http\FormRequest;

class TravelOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'                => 'nullable|string|exists:travel_orders,id',
            'status_id'         => 'nullable|integer|exists:travel_order_status,id',
            'origin_id'         => 'nullable|integer|exists:cities,id',
            'destination_id'    => 'nullable|integer|exists:cities,id',
            'travel_type_id'    => 'nullable|integer|exists:travel_types,id',
            'departure_date'    => 'nullable|date',
            'return_date'       => 'nullable|date|after_or_equal:departure_date',
        ];
    }

    public function filters(): array
    {
        return $this->only([
            'id',
            'status_id',
            'origin_id',
            'destination_id',
            'travel_type_id',
            'departure_date',
            'return_date',
        ]);
    }
}
