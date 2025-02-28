<?php

namespace App\Http\Requests\TravelOrders;

use App\Rules\OrderExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required', 'string', new OrderExistsRule($this->getClientId()),
            'status_id' => 'required|numeric|exists:travel_order_status,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'please_provide_the_travel_order_id',
            'id.string' => 'travel_order_id_must_be_a_string',
            'id.exists' => 'the_provided_travel_order_does_not_exist',
            'status_id.required' => 'please_provide_the_travel_order_status',
            'status_id.number' => 'status_must_be_a_number',
            'status_id.exists' => 'the_provided_status_is_not_valid',
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
