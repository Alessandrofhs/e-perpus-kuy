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
                                            <span class="badge bg-danger">
                                                {{ $loan->overdue_days }} hari
                                            </span>
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
                        <strong>Buku:</strong> <span id="info_book"></span><br>
                        <strong>Tenggat:</strong> <span id="info_due"></span>
                    </div>

                    {{-- Tanggal kembali --}}
                    <div class="mb-3">
                        <label for="actual_return_date" class="form-label">
                            Tanggal Pengembalian
                        </label>
                        <input type="date"
                               id="actual_return_date"
                               name="actual_return_date"
                               class="form-control"
                               max="{{ date('Y-m-d') }}"
                               value="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Preview denda --}}
                    <div id="finePreview" class="alert alert-warning d-none">
                        <i class="ti ti-alert-triangle"></i>
                        Keterlambatan: <strong id="preview_days"></strong> hari<br>
                        Estimasi denda: <strong id="preview_fine"></strong>
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
                    <i class="ti ti-check"></i> Konfirmasi Pengembalian
                </button>
            </div>
        </div>
    </div>
</div>
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

        // Hitung preview denda dengan tanggal hari ini
        hitungPreviewDenda(due, '{{ date('Y-m-d') }}');

        $('#returnModal').modal('show');
    });

    // ── Preview denda saat tanggal berubah ───────────────
    $('#actual_return_date').on('change', function () {
        let due    = $('#return_loan_id').closest('tr') // ambil dari data attribute
        let dueVal = $('.btn-return[data-id="' + $('#return_loan_id').val() + '"]').data('due');

        hitungPreviewDenda(dueVal, $(this).val());
    });

    function hitungPreviewDenda(dueDate, returnDate) {
        let due    = new Date(dueDate);
        let ret    = new Date(returnDate);
        let diffMs = ret - due;
        let days   = Math.floor(diffMs / (1000 * 60 * 60 * 24));

        if (days > 0) {
            let fine = days * 1000;
            $('#preview_days').text(days);
            $('#preview_fine').text('Rp ' + fine.toLocaleString('id-ID'));
            $('#finePreview').removeClass('d-none');
            $('#noFinePreview').addClass('d-none');
        } else {
            $('#finePreview').addClass('d-none');
            $('#noFinePreview').removeClass('d-none');
        }
    }

    function formatDate(dateStr) {
        let date    = new Date(dateStr);
        let options = { day: '2-digit', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // ── Simpan pengembalian ──────────────────────────────
    $('#saveReturn').click(function () {
        Swal.fire({
            title             : 'Konfirmasi Pengembalian?',
            text              : 'Pastikan buku sudah diterima secara fisik.',
            icon              : 'question',
            showCancelButton  : true,
            confirmButtonColor: '#28a745',
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
                        notes              : $('#notes').val(),
                    },
                    success: function (response) {
                        Swal.fire({
                            title            : 'Berhasil!',
                            text             : response.message,
                            icon             : response.overdue_days > 0 ? 'warning' : 'success',
                            timer            : 3000,
                            showConfirmButton : false
                        }).then(() => location.reload());
                    },
                    error: function (xhr) {
                        let response = xhr.responseJSON;
                        if (xhr.status === 422) {
                            $.each(response.errors, function (key, messages) {
                                toastr.error(messages[0]);
                            });
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    }
                });
            }
        });
    });

});
</script>
@endsection