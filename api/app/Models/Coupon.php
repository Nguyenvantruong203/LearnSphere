<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',             // fixed | percent
        'value',
        'usage_limit',
        'used_count',
        'min_order_amount',
        'valid_from',
        'valid_to',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    // === Relationships ===

    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'coupon_id');
    }

    // === Helpers ===

    /**
     * Kiểm tra coupon còn hiệu lực không
     */
    public function isValid($orderAmount = 0): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_from && now()->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_to && now()->gt($this->valid_to)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($orderAmount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Tính số tiền giảm
     */
    public function calculateDiscount($orderAmount): float
    {
        if (!$this->isValid($orderAmount)) {
            return 0;
        }

        if ($this->type === 'percent') {
            return round($orderAmount * ($this->value / 100), 2);
        }

        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount);
        }

        return 0;
    }
}
