<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'quota',
        'used_count',
        'is_active',
        'started_at',
        'expired_at'
    ];

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
        } else {
            $discount = $this->value;
        }

        return min($discount, $subtotal);
    }

    public function validate(float $subtotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Kupon tidak aktif.'];
        }

        if ($this->started_at && now()->lt($this->started_at)) {
            return ['valid' => false, 'message' => 'Kupon belum berlaku.'];
        }

        if ($this->expired_at && now()->gt($this->expired_at)) {
            return ['valid' => false, 'message' => 'Kupon sudah kedaluwarsa.'];
        }

        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return ['valid' => false, 'message' => 'Kuota kupon sudah habis.'];
        }

        if ($subtotal < $this->min_purchase) {
            return [
                'valid'   => false,
                'message' => 'Minimum pembelian Rp' . number_format($this->min_purchase, 0, ',', '.'),
            ];
        }

        return ['valid' => true, 'message' => 'Kupon berhasil diterapkan!'];
    }
}
