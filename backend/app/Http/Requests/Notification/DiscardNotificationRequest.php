<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class DiscardNotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|string|exists:notifications,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'please_provide_the_notification_id',
            'id.string' => 'notification_id_must_be_a_string',
            'id.exists' => 'the_provided_notification_does_not_exist',
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id') 
        ]);
    }
}
