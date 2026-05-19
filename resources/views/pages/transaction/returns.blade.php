@extends('layouts.main')
@section('title', 'Pengembalian Buku')
@section('content')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Pengembalian Buku</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Pengembalian</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Peminjaman Aktif</h5>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="dataTable" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tenggat</th>
                                    <th>Keterlambatan</th>
                                    <th>Estimasi Denda</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $loan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $loan->user->name }}</td>
                                    <td>{{ $loan->book->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                                    <td>
                                        @if ($loan->overdue_days > 0)
                                            <span class="badge bg-danger">{{ $loan->overdue_days }} hari</span>
                                        @else
                                            <span class="badge bg-success">Tepat waktu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($loan->total_fine > 0)
                                            <span class="text-danger fw-bold">
                                                Rp {{ number_format($loan->total_fine, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-success">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $loan->status_badge }}">
                                            {{ $loan->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-return"
                                                data-id="{{ $loan->id }}"
                                                data-name="{{ $loan->user->name }}"
                                                data-book="{{ $loan->book->title }}"
                                                data-due="{{ \Carbon\Carbon::parse($loan->due_date)->format('Y-m-d') }}">
                                            <i class="ti ti-arrow-back-up"></i> Proses Kembali
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pengembalian --}}
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="returnForm">
                    @csrf
                    <input type="hidden" id="return_loan_id" name="loan_id">

                    {{-- Info peminjaman --}}
                    <div class="alert alert-info">
                        <strong>Peminjam:</strong> <span id="info_name"></span><br>
                        <strong>Buku:</strong>     <span id="info_book"></span><br>
                        <strong>Tenggat:</strong>  <span id="info_due"></span>
                    </div>

                    {{-- Tanggal kembali --}}
                    <div class="mb-3">
                        <label for="actual_return_date" class="form-label">Tanggal Pengembalian</label>
                        <input type="date"
                               id="actual_return_date"
                               name="actual_return_date"
                               class="form-control"
                               max="{{ date('Y-m-d') }}"
                               value="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Preview denda --}}
                    <div id="finePreview" class="d-none">
                        <hr>
                        <h6 class="text-danger"><i class="ti ti-alert-triangle"></i> Ada Denda Keterlambatan</h6>
                        <table class="table table-sm table-borderless mb-3">
                            <tr>
                                <td class="text-muted">Keterlambatan</td>
                                <td>: <strong id="preview_days"></strong> hari</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Denda/Hari</td>
                                <td>: <strong>Rp 5.000</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Denda</td>
                                <td>: <strong class="text-danger" id="preview_fine"></strong></td>
                            </tr>
                        </table>

                        {{-- ✅ Opsi pembayaran langsung --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pembayaran Denda</label>
                            <div class="d-flex gap-2">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="fine_payment"
                                           id="payNow"
                                           value="paid"
                                           checked>
                                    <label class="form-check-label text-success fw-bold" for="payNow">
                                        <i class="ti ti-cash"></i> Bayar Sekarang
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="fine_payment"
                                           id="payLater"
                                           value="unpaid">
                                    <label class="form-check-label text-danger" for="payLater">
                                        <i class="ti ti-clock"></i> Bayar Nanti
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="noFinePreview" class="alert alert-success d-none">
                        <i class="ti ti-circle-check"></i> Pengembalian tepat waktu, tidak ada denda.
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (opsional)</label>
                        <textarea id="notes"
                                  name="notes"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Kondisi buku, dll..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-primary" id="saveReturn">
                    <i class="ti ti-check"></i> <span id="saveReturnLabel">Konfirmasi Pengembalian</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    .swal2-container { z-index: 99999 !important; }
</style>
@endsection
@section('scripts')
<script>
$(document).ready(function () {

    $('#dataTable').DataTable({
        ordering    : true,
        searching   : true,
        paging      : true,
        lengthChange: true,
        info        : true
    });
    // ── Buka modal pengembalian ──────────────────────────
    $(document).on('click', '.btn-return', function () {
        let id   = $(this).data('id');
        let name = $(this).data('name');
        let book = $(this).data('book');
        let due  = $(this).data('due');

        $('#return_loan_id').val(id);
        $('#info_name').text(name);
        $('#info_book').text(book);
        $('#info_due').text(formatDate(due));
        $('#actual_return_date').val('{{ date('Y-m-d') }}');
        $('#notes').val('');

        // Reset preview
        $('#finePreview').addClass('d-none');
        $('#noFinePreview').addClass('d-none');
        $('#saveReturnLabel').text('Konfirmasi Pengembalian');
        $('#payNow').prop('checked', true);

        hitungPreviewDenda(due, '{{ date('Y-m-d') }}');

        $('#returnModal').modal('show');
    });

    // ── Preview denda saat tanggal berubah ───────────────
    $('#actual_return_date').on('change', function () {
        let dueVal = $('.btn-return[data-id="' + $('#return_loan_id').val() + '"]').data('due');
        hitungPreviewDenda(dueVal, $(this).val());
    });

    // ── Update label tombol simpan saat opsi bayar berubah
    $(document).on('change', 'input[name="fine_payment"]', function () {
        if ($(this).val() === 'paid') {
            $('#saveReturnLabel').text('Konfirmasi & Bayar Denda');
        } else {
            $('#saveReturnLabel').text('Konfirmasi Pengembalian');
        }
    });

    function hitungPreviewDenda(dueDate, returnDate) {
        let due  = new Date(dueDate);
        let ret  = new Date(returnDate);
        
        // ✅ due dikurangi ret, bukan ret dikurangi due
        let days = Math.floor((ret - due) / (1000 * 60 * 60 * 24));

        if (days > 0) {
            let finePerDay = 5000; // ✅ Sesuaikan dengan blade (Rp 5.000)
            let fine = days * finePerDay;
            $('#preview_days').text(days);
            $('#preview_fine').text('Rp ' + fine.toLocaleString('id-ID'));
            $('#finePreview').removeClass('d-none');
            $('#noFinePreview').addClass('d-none');
            $('#saveReturnLabel').text('Konfirmasi & Bayar Denda');
        } else {
            $('#finePreview').addClass('d-none');
            $('#noFinePreview').removeClass('d-none');
            $('#saveReturnLabel').text('Konfirmasi Pengembalian');
        }
    }

    function formatDate(dateStr) {
        let date    = new Date(dateStr);
        let options = { day: '2-digit', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // ── Simpan pengembalian ──────────────────────────────
    $('#saveReturn').click(function () {
        let days = parseInt($('#preview_days').text()) || 0;
        let isPaying = $('input[name="fine_payment"]:checked').val() === 'paid';

        let confirmText = days > 0
            ? (isPaying
                ? `Denda <strong>${$('#preview_fine').text()}</strong> akan langsung dibayar.`
                : `Denda <strong>${$('#preview_fine').text()}</strong> akan dibayar nanti.`)
            : 'Pengembalian tepat waktu, tidak ada denda.';

        Swal.fire({
            title            : 'Konfirmasi Pengembalian?',
            html             : `Pastikan buku sudah diterima secara fisik.<br><br>${confirmText}`,
            icon             : 'question',
            showCancelButton  : true,
            confirmButtonColor: '#4680ff',
            cancelButtonColor : '#6c757d',
            confirmButtonText : 'Ya, Konfirmasi!',
            cancelButtonText  : 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : '/returns/store',
                    type: 'POST',
                    data: {
                        _token             : '{{ csrf_token() }}',
                        loan_id            : $('#return_loan_id').val(),
                        actual_return_date : $('#actual_return_date').val(),
                        fine_payment       : $('input[name="fine_payment"]:checked').val() ?? 'unpaid',
                        notes              : $('#notes').val(),
                    },
                    success: function (response) {
                        Swal.fire({
                            title            : 'Berhasil!',
                            text             : response.message,
                            icon             : response.fine_status === 'unpaid' ? 'warning' : 'success',
                            timer            : 2500,
                            showConfirmButton : false
                        }).then(() => location.reload());
                    },
                    error: function (xhr) {
                        let res = xhr.responseJSON;
                        if (xhr.status === 422) {
                            $.each(res.errors, function (key, messages) {
                                toastr.error(messages[0]);
                            });
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
@endsection