@extends('layouts.main')
@section('title', 'Users Maintenance')
@section('content')
<div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Users</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Users</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Users</a></li>
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
                <h5>User Maintenance</h5>
                <br>
                <button class="btn btn-primary btn-sm float-right" id="btnAdd">
                  <i class="ti ti-plus"></i> Add User
                </button>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="dataTable" class="table table-striped table-bordered nowrap">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Major</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $user)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->major }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                          <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $user->id }}">
                            <i class="ti ti-edit"></i> Edit
                          </button>
                          <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $user->id }}">
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
    <div class="modal fade" id="userModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">User Form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <form id="userForm" enctype="multipart/form-data">
              @csrf
              <input type="hidden" id="user_id" name="id">

              <div class="mb-3 text-center">
                <img id="previewPhoto" 
                    src="/default-user.png" 
                    style="width:100px;height:100px;object-fit:cover;border-radius:50%;cursor:pointer;">

                <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                <div>
                  <small>Click to change the picture</small>
                </div>
              </div>

              <div class="mb-2">
                <input type="text" id="name" name="name" class="form-control" placeholder="Name">
              </div>

              <div class="mb-2">
                <select id="major" name="major" class="form-control">
                  <option value="" class="disabled">Select Major</option>
                  <option value="siio">Sistem Informasi Industri Otomotif</option>
                  <option value="abo">Administrasi Bisnis Otomotif</option>
                  <option value="tro">Teknik Rekayasa Otomotif</option>
                  <option value="tio">Teknik Industri Otomotif</option>
                  <option value="tkp">Teknik Kimia Polimer</option>
                </select>
              </div>

              <div class="mb-2">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
              </div>

              <div class="mb-2">
                <select id="role" name="role" class="form-control">
                  <option value="" class="disabled">Select Role</option>
                  <option value="admin">Admin</option>
                  <option value="member">Member</option>
                </select>
              </div>
              <div class="mb-2">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
              </div>

              <div class="mb-2">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password">
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-x" style="font-size:12px;"></i> Close</button>
            <button class="btn btn-primary" id="saveUser"><i class="fa fa-save fa-sm" style="font-size:12px;"></i> Save</button>
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
      $('#userForm')[0].reset();
      $('#user_id').val('');
      $('#userModal').modal('show');
    });

    $('#previewPhoto').click(function(){
      $('#photo').click();
    });

    // preview gambar
    $('#photo').change(function(e){
      let file = e.target.files[0];
      if(file){
        let reader = new FileReader();
        reader.onload = function(e){
          $('#previewPhoto').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
      }
    });
    // klik edit
    $(document).on('click', '.btn-edit', function(){
      let id = $(this).data('id');

      $.get('/users/' + id, function(data){
        $('#user_id').val(data.id);
        $('#name').val(data.name);
        $('#major').val(data.major);
        $('#email').val(data.email);
        $('#role').val(data.role);
        $('#password').val('');
        $('#previewPhoto').attr('src', data.photo_url);

        $('#userModal').modal('show');
      });
    });

    // save
    $('#saveUser').click(function(){
      let id = $('#user_id').val();
      let url = id ? '/users/' + id : '/users';

      let formData = new FormData($('#userForm')[0]);

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
          $('#userModal').modal('hide');

          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data user berhasil disimpan',
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
            url: '/users/' + id,
            type: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function(){
              Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: 'User berhasil dihapus',
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