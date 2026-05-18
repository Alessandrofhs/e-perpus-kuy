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

        {{-- ── Stat Cards ─────────────────────────────────── --}}
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-2 f-w-400 text-muted">Total Buku</h6>
                            <h4 class="mb-0">{{ number_format($totalBook) }}</h4>
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
                            <h6 class="mb-2 f-w-400 text-muted">Total Pengguna</h6>
                            <h4 class="mb-0">{{ number_format($totalUser) }}</h4>
                        </div>
                        <div class="avatar bg-light-success p-2 rounded">
                            <i class="ti ti-users f-24 text-success"></i>
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
                            <h6 class="mb-2 f-w-400 text-muted">Total Peminjaman</h6>
                            <h4 class="mb-0">{{ number_format($totalLoan) }}</h4>
                        </div>
                        <div class="avatar bg-light-warning p-2 rounded">
                            <i class="ti ti-clipboard-list f-24 text-warning"></i>
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
                            <h6 class="mb-2 f-w-400 text-muted">Total Pengembalian</h6>
                            <h4 class="mb-0">{{ number_format($totalReturn) }}</h4>
                        </div>
                        <div class="avatar bg-light-danger p-2 rounded">
                            <i class="ti ti-arrow-back-up f-24 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Grafik Peminjaman ───────────────────────────── --}}
        <div class="col-md-12">
          <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                  <div>
                      <h5 class="mb-0">Grafik Peminjaman</h5>
                      <small class="text-muted">Tepat Waktu vs Terlambat</small>
                  </div>
                  {{-- ✅ Tombol toggle --}}
                  <div class="btn-group" role="group">
                      <button type="button"
                              class="btn btn-sm btn-primary"
                              id="btnMonthly">
                          Per Bulan
                      </button>
                      <button type="button"
                              class="btn btn-sm btn-outline-primary"
                              id="btnWeekly">
                          Per Minggu
                      </button>
                  </div>
              </div>
              <div class="card-body">
                  <div id="loanChart"></div>
              </div>
          </div>
      </div>

        {{-- ── Tabel Pengembalian Terakhir ─────────────────── --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Pengembalian Terakhir</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Buku</th>
                                    <th>Tenggat</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentReturns as $return)
                                @php
                                    $isLate = $return->actual_return_date->gt($return->loan->due_date);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $return->loan->user->name }}</td>
                                    <td>{{ $return->loan->book->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($return->loan->loan_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($return->loan->due_date)->format('d M Y') }}</td>
                                    <td>
                                        @if ($isLate)
                                            <span class="badge bg-danger">Terlambat</span>
                                        @else
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($return->fine)
                                            <span class="text-danger fw-bold">
                                                {{ $return->fine->formatted_amount }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Belum ada data pengembalian
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
@section('scripts')
<script>
    const onTimeMonthly  = {!! json_encode($onTimeMonthly) !!};
    const overdueMonthly = {!! json_encode($overdueMonthly) !!};
    const onTimeWeekly   = {!! json_encode($onTimeWeekly) !!};
    const overdueWeekly  = {!! json_encode($overdueWeekly) !!};
    const weeklyLabels   = {!! json_encode($weeklyLabels) !!};

    const monthlyLabels = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
</script>

<script>
$(document).ready(function () {

    const chartOptions = (categories, onTime, overdue) => ({
        chart: {
            type   : 'area',
            height : 350,
            toolbar: { show: false },
            zoom   : { enabled: false },
            animations: {
                enabled       : true,
                easing        : 'easeinout',
                speed         : 500,
            }
        },
        series: [
            { name: 'Tepat Waktu', data: onTime },
            { name: 'Terlambat',   data: overdue }
        ],
        colors: ['#4680ff', '#2ca87f'],
        fill: {
            type    : 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom   : 0.45,
                opacityTo     : 0.05,
                stops         : [0, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2,
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: categories,
            axisBorder: { show: false },
            axisTicks : { show: false },
        },
        yaxis: {
            min       : 0,
            tickAmount: 4,
            labels    : { formatter: (val) => Math.floor(val) }
        },
        grid: {
            borderColor    : '#e0e0e0',
            strokeDashArray: 4,
        },
        legend: {
            position       : 'bottom',
            horizontalAlign: 'center',
            markers        : { radius: 12 },
        },
        tooltip: {
            y: { formatter: (val) => val + ' peminjaman' }
        }
    });

    // ✅ Render default: per bulan
    const el    = document.querySelector('#loanChart');
    let chart   = new ApexCharts(el, chartOptions(monthlyLabels, onTimeMonthly, overdueMonthly));
    chart.render();

    // ✅ Toggle ke per bulan
    $('#btnMonthly').click(function () {
        chart.destroy();
        chart = new ApexCharts(el, chartOptions(monthlyLabels, onTimeMonthly, overdueMonthly));
        chart.render();

        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        $('#btnWeekly').removeClass('btn-primary').addClass('btn-outline-primary');
    });

    // ✅ Toggle ke per minggu
    $('#btnWeekly').click(function () {
        chart.destroy();
        chart = new ApexCharts(el, chartOptions(weeklyLabels, onTimeWeekly, overdueWeekly));
        chart.render();

        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        $('#btnMonthly').removeClass('btn-primary').addClass('btn-outline-primary');
    });

});
</script>
@endsection