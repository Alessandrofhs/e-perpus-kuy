@extends('layouts.main')
@section('title', 'Profile')

@section('content')

<div class="pc-content">

    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">

                <div class="col-md-12">

                    <div class="page-header-title">
                        <h5 class="m-b-10">My Profile</h5>
                    </div>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            Profile
                        </li>

                        <li class="breadcrumb-item active">
                            My Profile
                        </li>
                    </ul>

                </div>

            </div>
        </div>
    </div>

    <!-- Profile -->
    <div class="row">

      <div class="col-12">

          <div class="card">

              <div class="card-body">

                  <form 
                      action="{{ route('profile.update') }}"
                      method="POST"
                      enctype="multipart/form-data"
                  >

                      @csrf
                      @method('PUT')

                      <!-- Photo -->
                      <div class="text-center mb-5">

                          <img 
                              id="previewPhoto"
                              src="{{ $user->photo 
                                      ? asset('storage/' . $user->photo) 
                                      : asset('templates/dist/assets/images/user/avatar-2.jpg') }}"
                              alt="profile-photo"
                              class="rounded-circle shadow"
                              style="
                                  width: 150px;
                                  height: 150px;
                                  object-fit: cover;
                                  cursor: pointer;
                              "
                          >

                          <input 
                              type="file"
                              id="photo"
                              name="photo"
                              class="d-none"
                              accept="image/*"
                          >

                          <div class="mt-2">
                              <small>
                                  Click photo to change
                              </small>
                          </div>

                          <h4 class="mt-3 mb-1">
                              {{ $user->name }}
                          </h4>

                          <span class="badge bg-primary">
                              {{ ucfirst($user->role) }}
                          </span>

                      </div>

                      <!-- Name -->
                      <div class="row mb-3 align-items-center">

                          <label class="col-md-2 fw-bold">
                              Name
                          </label>

                          <div class="col-md-10">
                              <input 
                                  type="text"
                                  name="name"
                                  class="form-control"
                                  value="{{ old('name', $user->name) }}"
                              >
                          </div>

                      </div>

                      <!-- Email -->
                      <div class="row mb-3 align-items-center">

                          <label class="col-md-2 fw-bold">
                              Email
                          </label>

                          <div class="col-md-10">
                              <input 
                                  type="email"
                                  name="email"
                                  class="form-control"
                                  value="{{ old('email', $user->email) }}"
                              >
                          </div>

                      </div>

                      <!-- Major -->
                      <div class="row mb-3 align-items-center">

                          <label class="col-md-2 fw-bold">
                              Major
                          </label>

                          <div class="col-md-10">

                              <select 
                                  id="major" 
                                  name="major" 
                                  class="form-control"
                              >

                                  <option value="" disabled>
                                      Select Major
                                  </option>

                                  <option value="siio"
                                      {{ old('major', $user->major) == 'siio' ? 'selected' : '' }}>
                                      Sistem Informasi Industri Otomotif
                                  </option>

                                  <option value="abo"
                                      {{ old('major', $user->major) == 'abo' ? 'selected' : '' }}>
                                      Administrasi Bisnis Otomotif
                                  </option>

                                  <option value="tro"
                                      {{ old('major', $user->major) == 'tro' ? 'selected' : '' }}>
                                      Teknik Rekayasa Otomotif
                                  </option>

                                  <option value="tio"
                                      {{ old('major', $user->major) == 'tio' ? 'selected' : '' }}>
                                      Teknik Industri Otomotif
                                  </option>

                                  <option value="tkp"
                                      {{ old('major', $user->major) == 'tkp' ? 'selected' : '' }}>
                                      Teknik Kimia Polimer
                                  </option>

                              </select>

                          </div>

                      </div>

                      <!-- Role -->
                      <div class="row mb-3 align-items-center">

                          <label class="col-md-2 fw-bold">
                              Role
                          </label>

                          <div class="col-md-10">
                              <input 
                                  type="text"
                                  class="form-control"
                                  value="{{ ucfirst($user->role) }}"
                                  readonly
                              >
                          </div>

                      </div>

                      <!-- Button -->
                      <div class="text-end mt-4">

                          <button 
                              type="submit"
                              class="btn btn-warning"
                          >
                              <i class="ti ti-device-floppy"></i>
                              Save Changes
                          </button>

                      </div>

                  </form>

              </div>

          </div>

      </div>

  </div>

</div>

@endsection
@Section('scripts')
<script>

$('#previewPhoto').click(function () {
    $('#photo').click();
});

$('#photo').change(function (e) {

    let reader = new FileReader();

    reader.onload = function (e) {
        $('#previewPhoto').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);

});

</script>
@endsection