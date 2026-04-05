<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier',
        'service',
        'service_code',
        'cost',
        'resi',
        'status',
        'origin_city_id',
        'destination_city_id',
        'estimated_days',
        'tracking_history'
    ];

    protected $casts = [
        'tracking_history' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
