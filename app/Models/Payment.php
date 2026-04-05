<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'snap_token',
        'payment_method',
        'status',
        'amount',
        'paid_at',
        'payload',
        'expired_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
