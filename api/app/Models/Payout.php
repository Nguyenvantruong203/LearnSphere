<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $table = 'payouts';

    protected $fillable = [
        'instructor_id',
        'order_item_id',
        'total_amount',
        'platform_fee',
        'instructor_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'total_amount'      => 'decimal:2',
        'platform_fee'      => 'decimal:2',
        'instructor_amount' => 'decimal:2',
        'paid_at'           => 'datetime',
    ];

    /* =========================
     |  Relationships
     |=========================*/

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /* =========================
     |  Scopes & Helpers
     |=========================*/

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopePaid($q)
    {
        return $q->where('status', 'paid');
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
    }
}
