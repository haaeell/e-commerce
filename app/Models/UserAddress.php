<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'receiver_name',
        'phone',
        'address',
        'province',
        'city',
        'district',
        'subdistrict',
        'postal_code',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
