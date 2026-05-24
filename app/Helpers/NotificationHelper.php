<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    // Notif ke member: pinjaman disetujui
    public static function loanApproved($loan): void
    {
        Notification::create([
            'user_id' => $loan->user_id,
            'title'   => 'Peminjaman Disetujui',
            'message' => "Peminjaman buku \"{$loan->book->title}\" telah disetujui. Silakan ambil buku di perpustakaan.",
            'type'    => 'loan_approved',
            'loan_id' => $loan->id,
        ]);
    }

    // Notif ke member: pinjaman ditolak
    public static function loanRejected($loan): void
    {
        Notification::create([
            'user_id' => $loan->user_id,
            'title'   => 'Peminjaman Ditolak',
            'message' => "Peminjaman buku \"{$loan->book->title}\" ditolak oleh petugas.",
            'type'    => 'loan_rejected',
            'loan_id' => $loan->id,
        ]);
    }

    // Notif ke semua admin: ada pinjaman baru
    public static function loanCreated($loan): void
    {
        // Kirim ke semua admin
        $admins = \App\Models\User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title'   => 'Peminjaman Baru',
                'message' => "{$loan->user->name} mengajukan peminjaman buku \"{$loan->book->title}\".",
                'type'    => 'loan_created',
                'loan_id' => $loan->id,
            ]);
        }
    }

    // Notif ke member: buku dikembalikan + denda
    public static function bookReturned($loan, $overdueDays, $finePerDay): void
    {
        $message = $overdueDays > 0
            ? "Buku \"{$loan->book->title}\" telah dikembalikan dengan keterlambatan {$overdueDays} hari. Denda: Rp " . number_format($overdueDays * $finePerDay, 0, ',', '.')
            : "Buku \"{$loan->book->title}\" telah dikembalikan tepat waktu.";

        Notification::create([
            'user_id' => $loan->user_id,
            'title'   => 'Pengembalian Buku',
            'message' => $message,
            'type'    => 'returned',
            'loan_id' => $loan->id,
        ]);
    }
}