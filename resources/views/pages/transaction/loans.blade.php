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
                <h5 class="m-b-10">Loans</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Loans</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Loans</a></li>
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
                <h5>Loan Maintenance</h5>
                <br>
                <button class="btn btn-primary btn-sm float-right" id="btnAdd">
                  <i class="ti ti-plus"></i> Add Loan
                </button>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="dataTable" class="table table-striped table-bordered nowrap">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Book</th>
                        <th>Borrower</th>
                        <th>Officer</th>
                        <th>Loan Date</th>
                        <th>Return Date</th>
                        <th>Action</th>
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
                          <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $book->id }}">
                            <i class="ti ti-edit"></i> Edit
                          </button>
                          <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $book->id }}">
                            <i class="ti ti-trash"></i> Delete
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
    <div class="modal fade" id="bookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="bookModalTitle">Add Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="bookForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="book_id" name="id">

                    <!-- Cover -->
                    <div class="mb-3 text-center">
                        <img id="previewCover"
                            src="/default-book.png"
                            style="width:120px;height:160px;object-fit:cover;cursor:pointer;border-radius:8px;">

                        <input type="file" id="cover" name="cover" class="d-none" accept="image/*">

                        <div>
                        <small>klik buat ganti cover</small>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mb-2">
                        <input type="text" id="title" name="title" class="form-control" placeholder="Title" required>
                    </div>

                    <!-- Author -->
                    <div class="mb-2">
                        <input type="text" id="author" name="author" class="form-control" placeholder="Author" required>
                    </div>

                    <!-- Publisher -->
                    <div class="mb-2">
                        <input type="text" id="publisher" name="publisher" class="form-control" placeholder="Publisher">
                    </div>

                    <!-- Year -->
                    <div class="mb-2">
                        <input type="date" id="published_year" name="published_year" class="form-control" placeholder="Published Year">
                    </div>

                    <!-- Qty -->
                    <div class="mb-2">
                        <input type="number" id="qty" name="qty" class="form-control" placeholder="Quantity" min="1">
                    </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times" style="font-size:12px;"></i> Close
                    </button>

                    <button class="btn btn-primary" id="saveBook">
                    <i class="fa fa-save" style="font-size:12px;"></i> Save
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

    $('#btnAdd').click(function(){
      $('#bookForm')[0].reset();
      $('#book_id').val('');
      $('#bookModal').modal('show');
    });

    $('#previewCover').click(function(){
      $('#cover').click();
    });

    // preview gambar
    $('#cover').change(function(e){
      let file = e.target.files[0];
      if(file){
        let reader = new FileReader();
        reader.onload = function(e){
          $('#previewCover').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
      }
    });
    // klik edit
    $(document).on('click', '.btn-edit', function(){
      let id = $(this).data('id');

      $.get('/books/' + id, function(data){
        $('#book_id').val(data.id);
        $('#title').val(data.title);
        $('#author').val(data.author);
        $('#publisher').val(data.publisher);
        $('#published_year').val(data.published_year);
        $('#qty').val(data.qty);
        $('#previewCover').attr('src', data.cover_url);

        $('#bookModal').modal('show');
      });
    });

    // save
    $('#saveBook').click(function(){
      let id = $('#book_id').val();
      let url = id ? '/books/' + id : '/books';

      let formData = new FormData($('#bookForm')[0]);

      if(id){
        formData.append('_method', 'PUT');
      }

      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(){
          $('#bookModal').modal('hide');

          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data book berhasil disimpan',
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        },
        error: function(xhr){
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan!',
          });
        }
      });
    });
    
    // DELETE
    $(document).on('click', '.btn-delete', function(){
      let id = $(this).data('id');

      Swal.fire({
        title: 'Yakin?',
        text: "Data bakal kehapus permanen 😈",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: '/books/' + id,
            type: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function(){
              Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: 'Book berhasil dihapus',
                timer: 1500,
                showConfirmButton: false
              }).then(() => {
                location.reload();
              });
            },
            error: function(){
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal menghapus data',
              });
            }
          });

        }
      });
    });
  });
</script>
@endsection