<?php

namespace App\Models;

use App\Models\TravelOrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class TravelOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'origin_id',
        'destination_id',
        'departure_date',
        'return_date',
        'status_id',
        'travel_type_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(TravelOrderStatus::class, 'status_id');
    }

    public function origin()
    {
        return $this->belongsTo(Cities::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Cities::class, 'destination_id');
    }

    public function travelType()
    {
        return $this->belongsTo(TravelType::class, 'travel_type_id');
    }
}
