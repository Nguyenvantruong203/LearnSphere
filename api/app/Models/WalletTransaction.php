<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'description',
        'currency',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /* =========================
     |  Relationships
     |=========================*/

    public function wallet()
    {
        return $this->belongsTo(InstructorWallet::class, 'wallet_id');
    }

    public function instructor()
    {
        return $this->hasOneThrough(
            User::class,
            InstructorWallet::class,
            'id',           // Local key on InstructorWallet
            'id',           // Local key on User
            'wallet_id',    // FK on WalletTransaction
            'instructor_id' // FK on InstructorWallet
        );
    }

    /* =========================
     |  Helpers
     |=========================*/

    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }

    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }
}
