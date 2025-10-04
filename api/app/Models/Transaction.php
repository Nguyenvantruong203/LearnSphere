<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'provider',
        'transaction_code',
        'currency',
        'provider_txn_id',
        'provider_order_id',
        'signature',
        'raw_params',
        'ipn_received_at',
    ];

    protected $casts = [
        'raw_params' => 'array',
        'ipn_received_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Quan hệ: Transaction thuộc về Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
