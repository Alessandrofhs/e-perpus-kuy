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
            ->whereIn('status', ['active', 'overdue'])
            ->latest()
            ->get();

        return view('pages.transaction.returns', compact('loans'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang dapat memproses pengembalian'
            ], 403);
        }

        $validated = $request->validate([
            'loan_id'            => 'required|exists:loans,id',
            'actual_return_date' => 'required|date',
            'fine_payment'       => 'nullable|in:paid,unpaid', // ✅
            'notes'              => 'nullable|string|max:255',
        ]);

        $loan = Loan::with('book')->findOrFail($validated['loan_id']);

        if (!in_array($loan->status, ['active', 'overdue'])) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman ini tidak dapat diproses'
            ], 400);
        }

        if ($loan->bookReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Buku ini sudah pernah dikembalikan'
            ], 400);
        }

        $actualReturn = \Carbon\Carbon::parse($validated['actual_return_date']);
        $dueDate      = \Carbon\Carbon::parse($loan->due_date);

        // Catat pengembalian
        $bookReturn = Returns::create([
            'loan_id'            => $loan->id,
            'actual_return_date' => $actualReturn,
            'received_by'        => Auth::id(),
            'notes'              => $validated['notes'] ?? null,
        ]);

        // Hitung denda
        $overdueDays = $actualReturn->gt($dueDate)
            ? $actualReturn->diffInDays($dueDate)
            : 0;

        $fineStatus = null;

        if ($overdueDays > 0) {
            $finePerDay = 1000;
            $isPaidNow  = ($validated['fine_payment'] ?? 'unpaid') === 'paid'; // ✅

            Fine::create([
                'loan_id'      => $loan->id,
                'return_id'    => $bookReturn->id,
                'overdue_days' => $overdueDays,
                'fine_per_day' => $finePerDay,
                'total_amount' => $overdueDays * $finePerDay,
                'status'       => $isPaidNow ? 'paid' : 'unpaid',       // ✅
                'paid_at'      => $isPaidNow ? now() : null,             // ✅
            ]);

            $fineStatus = $isPaidNow ? 'paid' : 'unpaid';
        }

        $loan->update(['status' => 'returned']);
        $loan->book->increment('qty');

        $message = match(true) {
            $overdueDays > 0 && $fineStatus === 'paid'   => "Buku dikembalikan, denda Rp " . number_format($overdueDays * 1000, 0, ',', '.') . " telah dibayar.",
            $overdueDays > 0 && $fineStatus === 'unpaid' => "Buku dikembalikan, denda Rp " . number_format($overdueDays * 1000, 0, ',', '.') . " belum dibayar.",
            default                                       => "Buku berhasil dikembalikan tepat waktu.",
        };

        return response()->json([
            'success'      => true,
            'message'      => $message,
            'overdue_days' => $overdueDays,
            'fine_status'  => $fineStatus,
        ]);
    }
}
