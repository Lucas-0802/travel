<?php

namespace App\Http\Requests\TravelOrders;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\OrderExistsRule;
use Illuminate\Support\Facades\Auth;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required',
                'string',
                new OrderExistsRule($this->getClientId())
            ],
            'origin' => 'required|string',
            'status_id' => 'required|integer|exists:travel_order_status,id',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'please_provide_the_travel_order_id',
            'id.integer' => 'travel_order_id_must_be_an_integer',
            'id.exists' => 'the_provided_travel_order_does_not_exist',
            'origin.required' => 'please_provide_the_trip_origin',
            'origin.string' => 'origin_must_be_a_string',
            'status_id.required' => 'please_provide_the_travel_order_status',
            'status_id.integer' => 'travel_order_status_must_be_an_integer',
            'destination.required' => 'please_provide_the_trip_destination',
            'destination.string' => 'destination_must_be_a_string',
            'departure_date.required' => 'please_provide_the_departure_date',
            'departure_date.date' => 'departure_date_must_be_a_valid_date',
            'return_date.required' => 'please_provide_the_return_date',
            'return_date.date' => 'return_date_must_be_a_valid_date',
        ];
    }
    public function validationData()
    {
        return array_merge($this->all(), ['id' => $this->route('id')]);
    }

    public function getClientId(): string
    {
        return Auth::user()->id;
    }
}
