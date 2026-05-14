<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class LoanController extends Controller
{
    public function index() {
        $loans = Loan::with(['user', 'approver', 'book'])->get();
        $books = Book::orderBy('title', 'ASC')->get();

        $borrowers = User::where('role', 'member')
                        ->orderBy('name', 'ASC')
                        ->get();

        // User dengan role admin
        $approvers = User::where('role', 'admin')
                        ->orderBy('name', 'ASC')
                        ->get();
        return view('pages.transaction.loans', compact('loans', 'books', 'borrowers', 'approvers'));
    }
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi role
        if ($user->role !== 'member') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya member yang dapat melakukan peminjaman'
            ], 403);
        }

        // ✅ Tambah validasi due_date
        $validated = $request->validate([
            'book_id'   => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date'  => [
                'required',
                'date',
                'after_or_equal:loan_date',                          // ✅ Minimal sama dengan loan_date
                'before_or_equal:' . Carbon::parse($request->loan_date)->addDays(7)->toDateString(), // ✅ Maksimal +7 hari
            ],
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Validasi stok
        if ($book->qty <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku sedang habis'
            ], 400);
        }

        // ✅ Cek peminjaman aktif yang belum dikembalikan
        $activeLoan = Loan::where('user_id', $user->id)
            ->where('book_id', $validated['book_id'])
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($activeLoan) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih meminjam buku ini'
            ], 400);
        }

        // ✅ Simpan dengan due_date
        Loan::create([
            'user_id'   => $user->id,
            'book_id'   => $validated['book_id'],
            'loan_date' => $validated['loan_date'],
            'due_date'  => $validated['due_date'],
            'status'    => 'pending',
        ]);

        // ✅ Kurangi stok buku
        $book->decrement('qty');

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil diajukan, menunggu persetujuan'
        ]);
    }

    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        $loan->cover_url = $loan->cover 
            ? asset('storage/' . $loan->cover) 
            : asset('default-book.png');

        return response()->json($loan);
    }
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $data = $request->all();

        if($request->hasFile('cover')){
            $data['cover'] = $request->file('cover')->store('books', 'public');
        }

        $loan->update($data);

        return response()->json(['success' => true]);
    }
    public function destroy($id) {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully.'
        ]);
    }
    public function approve($id)
    {
        $user = Auth::user();

        // Pastikan hanya admin
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $loan = Loan::findOrFail($id);

        $loan->update([
            'approved_by' => $user->id,
            'status'      => 'approved',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan approved successfully'
        ]);
    }
}
