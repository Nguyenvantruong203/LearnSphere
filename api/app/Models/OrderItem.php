<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'course_id',
        'price_at_purchase',
        'final_price_per_item',
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'final_price_per_item' => 'decimal:2',
    ];

    // Quan hệ tới Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Quan hệ tới Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
