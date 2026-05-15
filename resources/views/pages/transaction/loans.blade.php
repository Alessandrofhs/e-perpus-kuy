@extends('layouts.main')
@section('title', 'Loans Maintenance')
@section('content')
<div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Peminjaman</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Peminjaman</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Peminjaman</a></li>
                <li class="breadcrumb-item" aria-current="page">Data</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
          <!-- Zero config table start -->
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5>Peminjaman</h5>
                <br>
                <button class="btn btn-primary btn-sm float-right" id="btnAdd">
                  <i class="ti ti-plus"></i> Tambah Pinjaman
                </button>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="dataTable" class="table table-striped table-bordered nowrap">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Buku</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tenggat Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($loans as $loan)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                        <td>{{ $loan->status }}</td>
                        <td>
                          @if (Auth::user()->role == 'member')
                            <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $loan->id }}">
                              <i class="ti ti-edit"></i> Ubah
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $loan->id }}">
                              <i class="ti ti-trash"></i> Hapus
                            </button>
                            <button class="btn btn-sm btn-primary btn-view" data-id="{{ $loan->id }}">
                              <i class="ti ti-eye"></i> Lihat
                            </button>
                          @endif
                          @if(Auth::user()->role == 'admin')
                            <button class="btn btn-sm btn-success btn-approve" data-id="{{ $loan->id }}">
                              <i class="ti ti-check"></i> Setuju
                            </button>
                            <button class="btn btn-sm btn-danger btn-reject" data-id="{{ $loan->id }}">
                              <i class="ti ti-x"></i> Tolak
                            </button>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- Language - Comma Decimal Place table end -->
        </div>
    </div>
    <div class="modal fade" id="loanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="loanModalTitle">Tambah Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="loanForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="loan_id" name="id">

                    <!-- Buku -->
                    <div class="mb-3">
                        <label for="book_id" class="form-label">
                            Buku
                        </label>

                        <select id="book_id"
                                name="book_id"
                                class="form-control select2"
                                required>

                            <option value="">Pilih Buku</option>

                            @foreach($books as $book)
                                <option value="{{ $book->id }}">
                                    {{ $book->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                   

                    <!-- Tanggal Pinjam -->
                    <div class="mb-3">
                        <label for="loan_date" class="form-label">
                            Tanggal Pinjam
                        </label>

                        <input type="date"
                               id="loan_date"
                               name="loan_date"
                               class="form-control"
                               min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Tenggat Waktu</label>
                        <input type="date"
                              id="due_date"
                              name="due_date"
                              class="form-control"
                              disabled> {{-- Disabled dulu sebelum loan_date dipilih --}}
                        <small class="text-muted">Pilih antara tanggal pinjam hingga maksimal 7 hari</small>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times" style="font-size:12px;"></i> Tutup
                </button>

                <button class="btn btn-primary" id="saveLoan" type="button">
                    <i class="fa fa-save" style="font-size:12px;"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    $('#dataTable').DataTable({
      "ordering": true,     // sorting nyala
      "searching": true,    // search nyala
      "paging": true,       // pagination biar ga numpuk
      "lengthChange": true, // bisa pilih jumlah data per page
      "info": true   
    });

    $('#btnAdd').click(function () {

        $('#loanForm')[0].reset();

        $('.select2').val(null).trigger('change');

        $('#loan_id').val('');

        $('#due_date').prop('disabled', true).val('');

        $('#loanModalTitle').text('Tambah Pinjaman');

        $('#loanModal').modal('show');

    });

    $('.select2').select2({
        dropdownParent: $('#loanModal'),
        width: '100%',
        placeholder: $(this).data('placeholder'),
        allowClear: true
    });

    $('#saveLoan').click(function (e) {
        e.preventDefault();

        // ✅ Validasi sederhana di sisi client
        if (!$('#book_id').val()) {
            toastr.error('Pilih buku terlebih dahulu');
            return;
        }

        if (!$('#loan_date').val()) {
            toastr.error('Pilih tanggal pinjam terlebih dahulu');
            return;
        }

        let id = $('#loan_id').val();
        let url = id ? '/loans/update/' + id : '/loans/store';

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                book_id:   $('#book_id').val(),
                loan_date: $('#loan_date').val(),
                due_date:  $('#due_date').val(), // ✅ Terisi otomatis
            },
            success: function (response) {
                toastr.success(response.message);
                $('#loanModal').modal('hide');
                $('#loanForm')[0].reset();
                $('.select2').val(null).trigger('change');
                location.reload();
            },
            error: function (xhr) {
                let response = xhr.responseJSON;

                // ✅ Tangkap error validasi Laravel (422)
                if (xhr.status === 422) {
                    let errors = response.errors;
                    $.each(errors, function (key, messages) {
                        toastr.error(messages[0]);
                    });
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    $(document).on('click', '.btn-edit', function () {
        let id = $(this).data('id');

        $.ajax({
            url  : '/loans/' + id,
            type : 'GET',
            success: function (response) {
                let loan = response.data;

                $('#loan_id').val(loan.id);
                $('#book_id').val(loan.book_id).trigger('change');
                $('#loan_date').val(loan.loan_date).trigger('change');

                // Set due_date setelah range terbentuk
                setTimeout(() => $('#due_date').val(loan.due_date), 100);

                $('#loanModalTitle').text('Ubah Pinjaman');
                $('#loanModal').modal('show');
            },
            error: function () {
                toastr.error('Gagal mengambil data peminjaman');
            }
        });
    });

    $(document).on('click', '.btn-delete', function () {
        let id = $(this).data('id');

        Swal.fire({
            title             : 'Hapus Peminjaman?',
            text              : 'Data yang dihapus tidak dapat dikembalikan.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#d33',
            cancelButtonColor : '#6c757d',
            confirmButtonText : 'Ya, Hapus!',
            cancelButtonText  : 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : '/loans/delete/' + id,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        Swal.fire({
                            title            : 'Terhapus!',
                            text             : response.message,
                            icon             : 'success',
                            timer            : 1500,
                            showConfirmButton : false
                        }).then(() => location.reload());
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });

    $('#loan_date').on('change', function () {
        let loanDate = new Date($(this).val());

        if (!isNaN(loanDate)) {
            // Hitung batas maksimal: +7 hari
            let maxDate = new Date(loanDate);
            maxDate.setDate(maxDate.getDate() + 7);

            // Format ke YYYY-MM-DD
            let minStr = loanDate.toISOString().split('T')[0];
            let maxStr = maxDate.toISOString().split('T')[0];

            // ✅ Set min & max, lalu aktifkan input
            $('#due_date')
                .attr('min', minStr)
                .attr('max', maxStr)
                .val(maxStr)         // Default ke hari maksimal
                .prop('disabled', false);
        }
    });
  });
</script>
@endsection