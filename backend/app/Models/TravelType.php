<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'travel_types';
    protected $fillable = ['name'];

    public function travelOrders() : HasMany
    {
        return $this->hasMany(TravelOrder::class);
    }
}
