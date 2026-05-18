<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'approved_by',
        'book_id',
        'loan_date',
        'due_date',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Admin yang approve
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Buku yang dipinjam
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function Returns()
    {
        return $this->hasOne(Returns::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }
    public function getOverdueDaysAttribute(): int
    {
        if (!in_array($this->status, ['active', 'overdue'])) return 0;

        $due = $this->due_date;

        return today()->gt($due) ? today()->diffInDays($due) : 0;
    }

    // Hitung total denda real-time (sebelum dikembalikan)
    public function getTotalFineAttribute(): int
    {
        return $this->overdue_days * 1000; // Rp 1.000/hari
    }
}
