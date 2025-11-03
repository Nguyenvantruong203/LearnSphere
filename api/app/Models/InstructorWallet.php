<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorWallet extends Model
{
    use HasFactory;

    protected $table = 'instructor_wallets';

    protected $fillable = [
        'instructor_id',
        'balance',
        'total_earned',
        'total_withdrawn',
        'currency',
    ];

    protected $casts = [
        'balance'         => 'decimal:2',
        'total_earned'    => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    /* =========================
     |  Relationships
     |=========================*/

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    /* =========================
     |  Helpers
     |=========================*/

    public function credit(float $amount, string $description = null): void
    {
        $this->increment('balance', $amount);
        $this->increment('total_earned', $amount);

        $this->transactions()->create([
            'amount'      => $amount,
            'type'        => 'credit',
            'description' => $description ?? 'Course revenue',
            'currency'    => $this->currency,
        ]);
    }

    public function debit(float $amount, string $description = null): bool
    {
        if ($this->balance < $amount) {
            return false;
        }

        $this->decrement('balance', $amount);
        $this->increment('total_withdrawn', $amount);

        $this->transactions()->create([
            'amount'      => $amount,
            'type'        => 'debit',
            'description' => $description ?? 'Withdrawal',
            'currency'    => $this->currency,
        ]);

        return true;
    }

    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 2) . ' ' . $this->currency;
    }
}
