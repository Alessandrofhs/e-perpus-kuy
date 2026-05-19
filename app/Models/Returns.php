<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $fillable = [
        'loan_id',
        'actual_return_date',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'actual_return_date' => 'date',
    ];

    // ── Relationships ────────────────────────────────────

    // Pengembalian ini milik peminjaman mana
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // Petugas yang menerima pengembalian
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Denda yang terkait pengembalian ini (jika ada)
    public function fine()
    {
        return $this->hasOne(Fine::class, 'return_id');
    }
    public function getIsLateAttribute(): bool
    {
        return $this->actual_return_date->gt($this->loan->due_date);
    }

    // Hitung hari terlambat saat dikembalikan
    public function getLateDaysAttribute(): int
    {
        if (!$this->is_late) return 0;

        return $this->actual_return_date->diffInDays($this->loan->due_date);
    }
}
