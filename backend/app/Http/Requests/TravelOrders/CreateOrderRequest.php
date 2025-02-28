<?php

namespace App\Http\Requests\TravelOrders;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'origin_id' => 'required|numeric|exists:cities,id',
            'destination_id' => 'required|numeric|exists:cities,id',
            'travel_type_id' => 'required|numeric|exists:travel_types,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:departure_date',
        ];
    }

    public function messages()
    {
        return [
            'origin_id.required' => 'please_provide_the_trip_origin',
            'origin_id.numeric' => 'origin_must_be_a_number',
            'origin_id.exists' => 'origin_does_not_exist',
            'destination_id.required' => 'please_provide_the_trip_destination',
            'destination_id.numeric' => 'destination_must_be_a_number',
            'destination_id.exists' => 'destination_does_not_exist',
            'travel_type_id.required' => 'please_provide_the_trip_type',
            'travel_type_id.numeric' => 'travel_type_must_be_a_number',
            'travel_type_id.exists' => 'travel_type_does_not_exist',
            'departure_date.required' => 'please_provide_the_departure_date',
            'departure_date.date' => 'departure_date_must_be_a_valid_date',
            'return_date.required' => 'please_provide_the_return_date',
            'return_date.date' => 'return_date_must_be_a_valid_date',
        ];
    }
}

