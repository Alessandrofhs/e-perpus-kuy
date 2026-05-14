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
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="dataTable" class="table table-striped table-bordered nowrap">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Buku</th>
                        <th>Peminjam</th>
                        <th>Petugas</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($loans as $loan)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->approver->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('dd-mm-yyyy') }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('dd-mm-yyyy') }}</td>
                        <td>
                          <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $loan->id }}">
                            <i class="ti ti-edit"></i> Ubah
                          </button>
                          <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $loan->id }}">
                            <i class="ti ti-trash"></i> Hapus
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
                               class="form-control">
                    </div>

                    <!-- Tanggal Pengembalian -->
                    <div class="mb-3">
                        <label for="return_date" class="form-label">
                            Tanggal Pengembalian
                        </label>

                        <input type="date"
                               id="return_date"
                               name="return_date"
                               class="form-control">
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times" style="font-size:12px;"></i> Tutup
                </button>

                <button class="btn btn-primary" id="saveLoan">
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

        $('#loanModalTitle').text('Add Loan');

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

        let id = $('#loan_id').val();

        let url = id
            ? '/loans/update/' + id
            : '/loans/store';

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                book_id: $('#book_id').val(),
                loan_date: $('#loan_date').val(),
                return_date: $('#return_date').val(),
            },

            success: function (response) {

                alert(response.message);

                $('#loanModal').modal('hide');

                $('#loanForm')[0].reset();

                $('.select2').val(null).trigger('change');

                location.reload();
            }
        });

    });

  });
</script>
@endsection