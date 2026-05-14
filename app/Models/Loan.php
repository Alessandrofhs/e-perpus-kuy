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
}
