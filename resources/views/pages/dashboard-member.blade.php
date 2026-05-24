@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- Greeting --}}
        <div class="col-12 mb-2">
            <h5 class="text-muted">
                Selamat datang, <span class="text-primary fw-bold">{{ Auth::user()->name }}</span> 👋
            </h5>
        </div>

        {{-- ── Stat Cards ──────────────────────────────── --}}
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-2 f-w-400 text-muted">Sedang Dipinjam</h6>
                            <h4 class="mb-0 text-primary">{{ $activeLoans }}</h4>
                        </div>
                        <div class="avatar bg-light-primary p-2 rounded">
                            <i class="ti ti-book f-24 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-2 f-w-400 text-muted">Total Pinjaman</h6>
                            <h4 class="mb-0 text-success">{{ $totalLoans }}</h4>
                        </div>
                        <div class="avatar bg-light-success p-2 rounded">
                            <i class="ti ti-clipboard-list f-24 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-2 f-w-400 text-muted">Pengembalian</h6>
                            <h4 class="mb-0 text-info">{{ $totalReturned }}</h4>
                        </div>
                        <div class="avatar bg-light-info p-2 rounded">
                            <i class="ti ti-arrow-back-up f-24 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-2 f-w-400 text-muted">Belum Bayar</h6>
                            <h4 class="mb-0 text-danger">
                                Rp {{ number_format($totalFine, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="avatar bg-light-danger p-2 rounded">
                            <i class="ti ti-cash f-24 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Peminjaman Aktif ─────────────────────────── --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Peminjaman Aktif Saya</h5>
                </div>
                <div class="card-body">
                    @forelse ($activeLoanList as $loan)
                    @php
                        $daysLeft    = now()->diffInDays($loan->due_date, false);
                        $isOverdue   = $daysLeft < 0;
                        $overdueDays = $loan->overdue_days;
                    @endphp
                    <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded border
                        {{ $isOverdue ? 'border-danger bg-light' : 'border-success' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar bg-light-primary p-2 rounded">
                                <i class="ti ti-book f-20 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $loan->book->title }}</h6>
                                <small class="text-muted">
                                    Tenggat: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            @if ($isOverdue)
                                <span class="badge bg-danger mb-1">
                                    Terlambat {{ $overdueDays }} hari
                                </span><br>
                                <small class="text-danger fw-bold">
                                    Denda: Rp {{ number_format($overdueDays * 5000, 0, ',', '.') }}
                                </small>
                            @elseif ($daysLeft == 0)
                                <span class="badge bg-warning">Jatuh tempo hari ini!</span>
                            @elseif ($daysLeft <= 2)
                                <span class="badge bg-warning">{{ $daysLeft }} hari lagi</span>
                            @else
                                <span class="badge bg-success">{{ $daysLeft }} hari lagi</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-book-off" style="font-size:40px;"></i>
                        <p class="mt-2">Tidak ada peminjaman aktif</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── Riwayat Terakhir ─────────────────────────── --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Riwayat Peminjaman Terakhir</h5>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tenggat</th>
                                    <th>Status</th>
                                    <th>Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentLoans as $loan)
                                <tr>
                                    <td>{{ $loan->book->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $loan->status_badge }}">
                                            {{ $loan->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($loan->fine)
                                            <span class="{{ $loan->fine->status === 'paid' ? 'text-success' : 'text-danger' }}">
                                                {{ $loan->fine->formatted_amount }}
                                                <small>({{ $loan->fine->status === 'paid' ? 'Lunas' : 'Belum Dibayar' }})</small>
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada riwayat peminjaman
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection