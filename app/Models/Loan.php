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

        $due   = \Carbon\Carbon::parse($this->due_date)->startOfDay();
        $today = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();

        if ($today->gt($due)) {
            // ✅ Hitung dari due ke today, bukan sebaliknya
            return (int) $due->diffInDays($today);
        }

        return 0;
    }


    // Hitung total denda real-time (sebelum dikembalikan)
    public function getTotalFineAttribute(): int
    {
        return $this->overdue_days * 5000; // Rp 5.000/hari
    }

    // Format status agar lebih rapi
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu',
            'active'   => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'rejected' => 'Ditolak',
            'overdue'  => 'Terlambat',
            default    => ucfirst($this->status)
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'warning',
            'active'   => 'success',
            'returned' => 'info',
            'rejected' => 'danger',
            'overdue'  => 'dark',
            default    => 'secondary'
        };
    }
}
