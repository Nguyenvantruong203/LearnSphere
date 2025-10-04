<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'coupon_id',
        'total_price',
        'discount_amount',
        'final_price',
        'status',
    ];

    protected $casts = [
        'total_price'     => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price'     => 'decimal:2',
        'created_at'      => 'datetime',
    ];

    // === Relationships ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // === Helpers ===

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending_payment';
    }

    public function applyCoupon(Coupon $coupon): void
    {
        if ($coupon->isValid($this->total_price)) {
            $discount = $coupon->calculateDiscount($this->total_price);
            $this->discount_amount = $discount;
            $this->final_price     = $this->total_price - $discount;
            $this->coupon_id       = $coupon->id;
        }
    }
}
