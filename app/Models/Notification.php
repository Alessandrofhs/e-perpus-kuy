<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications'; 
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'loan_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // ✅ Cek apakah sudah dibaca
    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }
}
