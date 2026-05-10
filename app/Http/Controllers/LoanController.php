<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index() {
        $loans = Loan::with(['user', 'approver', 'book'])->get();
        return view('pages.transaction.loans', compact('loans'));
    }
    public function store(Request $request)
    {
        $user = Auth::user();

        // Pastikan hanya member
        if ($user->role !== 'member') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'book_id'   => 'required|integer|exists:books,id',
            'loan_date' => 'required|date',
        ]);

        Loan::create([
            'user_id'    => $user->id,
            'book_id'    => $validated['book_id'],
            'loan_date'  => $validated['loan_date'],
            'status'     => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan request created successfully'
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
