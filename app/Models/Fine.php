<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'loan_id',
        'return_id',
        'overdue_days',
        'fine_per_day',
        'total_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────

    // Denda ini milik peminjaman mana
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // Denda ini dari pengembalian mana
    public function Returns()
    {
        return $this->belongsTo(Returns::class, 'return_id');
    }

    // ── Accessors ────────────────────────────────────────

    // Format total_amount ke Rupiah
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    // Cek apakah denda sudah lunas
    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
    }
}
