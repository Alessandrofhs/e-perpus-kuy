<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Returns;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function index()
    {
        // Hanya tampilkan loan yang statusnya active atau overdue
        $loans = Loan::with(['user', 'book', 'returns', 'fine'])
            ->whereIn('status', ['dipinjam', 'telat'])
            ->latest()
            ->get();

        return view('pages.transaction.returns', compact('loans'));
    }

    public function store(Request $request)
    {
        // Validasi role
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang dapat memproses pengembalian'
            ], 403);
        }

        $validated = $request->validate([
            'loan_id'            => 'required|exists:loans,id',
            'actual_return_date' => 'required|date',
            'notes'              => 'nullable|string|max:255',
        ]);

        $loan = Loan::with('book')->findOrFail($validated['loan_id']);

        // Pastikan loan masih active atau overdue
        if (!in_array($loan->status, ['dipinjam', 'telat'])) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman ini tidak dapat diproses'
            ], 400);
        }

        // Pastikan belum pernah dikembalikan
        if ($loan->bookReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Buku ini sudah pernah dikembalikan'
            ], 400);
        }

        $actualReturn = \Carbon\Carbon::parse($validated['actual_return_date']);
        $dueDate      = \Carbon\Carbon::parse($loan->due_date);

        // ── Catat pengembalian ───────────────────────────
        $Returns = Returns::create([
            'loan_id'            => $loan->id,
            'actual_return_date' => $actualReturn,
            'received_by'        => Auth::id(),
            'notes'              => $validated['notes'] ?? null,
        ]);

        // ── Hitung denda jika terlambat ──────────────────
        $overdueDays = $actualReturn->gt($dueDate)
            ? $actualReturn->diffInDays($dueDate)
            : 0;

        if ($overdueDays > 0) {
            $finePerDay = 2000; // Rp 1.000/hari

            Fine::create([
                'loan_id'      => $loan->id,
                'return_id'    => $Returns->id,
                'overdue_days' => $overdueDays,
                'fine_per_day' => $finePerDay,
                'total_amount' => $overdueDays * $finePerDay,
                'status'       => 'unpaid',
            ]);
        }

        // ── Update status loan & kembalikan stok ─────────
        $loan->update(['status' => 'returned']);
        $loan->book->increment('qty');

        $message = $overdueDays > 0
            ? "Buku dikembalikan dengan keterlambatan {$overdueDays} hari. Denda: Rp " . number_format($overdueDays * 2000, 0, ',', '.')
            : 'Buku berhasil dikembalikan tepat waktu';

        return response()->json([
            'success'      => true,
            'message'      => $message,
            'overdue_days' => $overdueDays,
            'total_fine'   => $overdueDays * 1000,
        ]);
    }
}
