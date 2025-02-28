<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TravelOrder;

class OrderExistsRule implements Rule
{
    protected $clientId;
    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }
    public function passes($attribute, $value): bool
    {
        return TravelOrder::where(function ($query) {
            $query->where('user_id', $this->clientId)
                ->orWhereHas('user', function ($query) {
                    $query->where('status_id', 'admin');
                });
        })
            ->where('id', $value)
            ->exists();
    }

    public function message(): string
    {
        return 'the_provided_travel_order_does_not_exist';
    }
}
