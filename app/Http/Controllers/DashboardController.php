<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Returns;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser   = User::count();
        $totalBook   = Book::count();
        $totalLoan   = Loan::count();
        $totalReturn = Returns::count();

        // ── Data BULANAN ─────────────────────────────────────
        $loanOnTime = Loan::where('status', 'returned')
            ->whereHas('returns', function ($q) {
                $q->whereColumn('actual_return_date', '<=', 'loans.due_date');
            })
            ->selectRaw('MONTH(loan_date) as period, COUNT(*) as total')
            ->whereYear('loan_date', now()->year)
            ->groupBy('period')
            ->pluck('total', 'period');

        $loanOverdue = Loan::where('status', 'returned')
            ->whereHas('returns', function ($q) {
                $q->whereColumn('actual_return_date', '>', 'loans.due_date');
            })
            ->selectRaw('MONTH(loan_date) as period, COUNT(*) as total')
            ->whereYear('loan_date', now()->year)
            ->groupBy('period')
            ->pluck('total', 'period');

        $onTimeMonthly  = collect(range(1, 12))->map(fn($m) => $loanOnTime[$m]  ?? 0)->values();
        $overdueMonthly = collect(range(1, 12))->map(fn($m) => $loanOverdue[$m] ?? 0)->values();

        // ── Data MINGGUAN (8 minggu terakhir) ────────────────
        $loanOnTimeWeekly = Loan::where('status', 'returned')
            ->whereHas('returns', function ($q) {
                $q->whereColumn('actual_return_date', '<=', 'loans.due_date');
            })
            ->selectRaw('WEEK(loan_date) as period, COUNT(*) as total')
            ->whereBetween('loan_date', [now()->subWeeks(7)->startOfWeek(), now()->endOfWeek()])
            ->groupBy('period')
            ->pluck('total', 'period');

        $loanOverdueWeekly = Loan::where('status', 'returned')
            ->whereHas('returns', function ($q) {
                $q->whereColumn('actual_return_date', '>', 'loans.due_date');
            })
            ->selectRaw('WEEK(loan_date) as period, COUNT(*) as total')
            ->whereBetween('loan_date', [now()->subWeeks(7)->startOfWeek(), now()->endOfWeek()])
            ->groupBy('period')
            ->pluck('total', 'period');

        // Label minggu: "Minggu 1", "Minggu 2", dst
        $weeklyLabels = collect(range(7, 0))->map(function ($i) {
            $start = now()->subWeeks($i)->startOfWeek()->format('d M');
            $end   = now()->subWeeks($i)->endOfWeek()->format('d M');
            return $start . ' - ' . $end;
        })->values();

        $weekNumbers = collect(range(7, 0))->map(fn($i) => now()->subWeeks($i)->startOfWeek()->weekOfYear)->values();

        $onTimeWeekly  = $weekNumbers->map(fn($w) => $loanOnTimeWeekly[$w]  ?? 0)->values();
        $overdueWeekly = $weekNumbers->map(fn($w) => $loanOverdueWeekly[$w] ?? 0)->values();

        // ── Pengembalian terakhir ─────────────────────────────
        $recentReturns = Returns::with(['loan.user', 'loan.book', 'fine'])
            ->latest()
            ->take(10)
            ->get();

        return view('pages.dashboard', compact(
            'totalUser', 'totalBook', 'totalLoan', 'totalReturn',
            'onTimeMonthly', 'overdueMonthly',
            'onTimeWeekly', 'overdueWeekly', 'weeklyLabels',
            'recentReturns'
        ));
    }
}
