<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Returns;
use App\Models\Fine;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->memberDashboard($user);
    }

    private function adminDashboard()
    {
        $totalUser   = User::count();
        $totalBook   = Book::count();
        $totalLoan   = Loan::count();
        $totalReturn = Returns::count();

        $loanOnTime = Loan::where('status', 'returned')
            ->whereHas('returns', fn($q) => $q->whereColumn('actual_return_date', '<=', 'loans.due_date'))
            ->selectRaw('MONTH(loan_date) as period, COUNT(*) as total')
            ->whereYear('loan_date', now()->year)
            ->groupBy('period')->pluck('total', 'period');

        $loanOverdue = Loan::where('status', 'returned')
            ->whereHas('returns', fn($q) => $q->whereColumn('actual_return_date', '>', 'loans.due_date'))
            ->selectRaw('MONTH(loan_date) as period, COUNT(*) as total')
            ->whereYear('loan_date', now()->year)
            ->groupBy('period')->pluck('total', 'period');

        $onTimeMonthly  = collect(range(1, 12))->map(fn($m) => $loanOnTime[$m]  ?? 0)->values();
        $overdueMonthly = collect(range(1, 12))->map(fn($m) => $loanOverdue[$m] ?? 0)->values();

        $loanOnTimeWeekly = Loan::where('status', 'returned')
            ->whereHas('returns', fn($q) => $q->whereColumn('actual_return_date', '<=', 'loans.due_date'))
            ->selectRaw('WEEK(loan_date) as period, COUNT(*) as total')
            ->whereBetween('loan_date', [now()->subWeeks(7)->startOfWeek(), now()->endOfWeek()])
            ->groupBy('period')->pluck('total', 'period');

        $loanOverdueWeekly = Loan::where('status', 'returned')
            ->whereHas('returns', fn($q) => $q->whereColumn('actual_return_date', '>', 'loans.due_date'))
            ->selectRaw('WEEK(loan_date) as period, COUNT(*) as total')
            ->whereBetween('loan_date', [now()->subWeeks(7)->startOfWeek(), now()->endOfWeek()])
            ->groupBy('period')->pluck('total', 'period');

        $weeklyLabels = collect(range(7, 0))->map(function ($i) {
            $start = now()->subWeeks($i)->startOfWeek()->format('d M');
            $end   = now()->subWeeks($i)->endOfWeek()->format('d M');
            return $start . ' - ' . $end;
        })->values();

        $weekNumbers   = collect(range(7, 0))->map(fn($i) => now()->subWeeks($i)->startOfWeek()->weekOfYear)->values();
        $onTimeWeekly  = $weekNumbers->map(fn($w) => $loanOnTimeWeekly[$w]  ?? 0)->values();
        $overdueWeekly = $weekNumbers->map(fn($w) => $loanOverdueWeekly[$w] ?? 0)->values();

        $recentReturns = Returns::with(['loan.user', 'loan.book', 'fine'])
            ->latest()->take(10)->get();

        return view('pages.dashboard', compact(
            'totalUser', 'totalBook', 'totalLoan', 'totalReturn',
            'onTimeMonthly', 'overdueMonthly',
            'onTimeWeekly', 'overdueWeekly', 'weeklyLabels',
            'recentReturns'
        ));
    }

    private function memberDashboard($user)
    {
        // ── Stat cards ───────────────────────────────────────
        $activeLoans = Loan::where('user_id', $user->id)
            ->whereIn('status', ['active', 'overdue'])
            ->count();

        $totalLoans = Loan::where('user_id', $user->id)->count();

        $totalReturned = Loan::where('user_id', $user->id)
            ->where('status', 'returned')
            ->count();

        $totalFine = Fine::whereHas('loan', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'unpaid')
            ->sum('total_amount');

        // ── Peminjaman aktif + estimasi denda ────────────────
        $activeLoanList = Loan::with(['book'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'overdue'])
            ->get();

        // ── Riwayat 5 terakhir ───────────────────────────────
        $recentLoans = Loan::with(['book', 'fine'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.dashboard-member', compact(
            'activeLoans', 'totalLoans', 'totalReturned',
            'totalFine', 'activeLoanList', 'recentLoans'
        ));
    }
}
