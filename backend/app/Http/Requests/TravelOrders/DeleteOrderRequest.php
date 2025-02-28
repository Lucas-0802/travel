<?php

namespace App\Http\Requests\TravelOrders;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\OrderExistsRule;
use Illuminate\Support\Facades\Auth;

class DeleteOrderRequest extends FormRequest
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
            ]
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'please_provide_the_travel_order_id',
            'id.integer' => 'travel_order_id_must_be_an_integer',
            'id.exists' => 'the_provided_travel_order_does_not_exist'
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
