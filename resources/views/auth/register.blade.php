<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Sign up | E-Perpus-Kuy</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
<link rel="icon" href="{{ asset('templates/dist/assets/images/logo-e-perpus-mini.png') }}" type="image/x-icon"> <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="{{ asset('templates/dist/assets/fonts/tabler-icons.min.css') }}" >
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="{{ asset('templates/dist/assets/fonts/feather.css') }}" >
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="{{ asset('templates/dist/assets/fonts/fontawesome.css') }}" >
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="{{ asset('templates/dist/assets/fonts/material.css') }}" >
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="{{ asset('templates/dist/assets/css/style.css') }}" id="main-style-link" >
<link rel="stylesheet" href="{{ asset('templates/dist/assets/css/style-preset.css') }}" >
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->
<form action="{{ route('register.process') }}" method="POST">
  @csrf
  <div class="auth-main">
    <div class="auth-wrapper v3">
        <div class="auth-form">
          <div class="auth-header">
            <a href="#"><img src="{{ asset('templates/dist/assets/images/logo-e-perpus-kuy-hori.png') }}" alt="img" style="width: 180px"></a>
          </div>
          <div class="card my-5">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="mb-0"><b>Sign up</b></h3>
                <a href="{{ route('login') }}" class="link-primary">Sudah mempunyai akun?</a>
              </div>
              <div class="row">
                <div class="form-group mb-3">
                  <label class="form-label"> Nama Lengkap*</label>
                  <input type="text" class="form-control" name="name" placeholder="Nama lengkap" required>
                </div>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Jurusan</label>
                <select name="major" class="form-control">
                  <option value="" disabled selected>Pilih jurusan</option>
                  <option value="tkp">Teknik Kimia Polimer</option>
                  <option value="siio">Sistem Informasi Industri Otomotif</option>
                  <option value="tio">Teknik Industri Otomotif</option>
                  <option value="tro">Teknik Rekayasa Otomotif</option>
                  <option value="abo">Administasi Bisnis Otomotif</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Email*</label>
                <input type="email" class="form-control" name="email"placeholder="Email" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password" required>
              </div>
              <p class="mt-4 text-sm text-muted">Dengan mendaftar, Anda menyetujui ketentuan kami<a href="#" class="text-primary"> Ketentuan dan Layanan </a> and <a href="#" class="text-primary"> Kebijakan Privasi</a></p>
              <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary">Buat Akun</button>
              </div>
            </div>
          </div>
          <div class="auth-footer row">
            <!-- <div class=""> -->
              <div class="col my-1">
                <p class="m-0">Copyright © <a href="#">Codedthemes</a></p>
              </div>
              <div class="col-auto my-1">
                <ul class="list-inline footer-link mb-0">
                  <li class="list-inline-item"><a href="#">Home</a></li>
                  <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                  <li class="list-inline-item"><a href="#">Contact us</a></li>
                </ul>
              </div>
            <!-- </div> -->
          </div>
        </div>
    </div>
  </div>
  </form>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->
  <script src="{{ asset('templates/dist/assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/plugins/feather.min.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>

  toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: "toast-top-right",
      timeOut: "3000"
  };

  </script>
  
    @if ($errors->any())

      <script>
          $(document).ready(function () {

              @foreach ($errors->all() as $error)
                  toastr.error(@json($error));
              @endforeach

          });
      </script>

  @endif
  <script>layout_change('light');</script>
  
  
  
  
  <script>change_box_container('false');</script>
  
  
  
  <script>layout_rtl_change('false');</script>
  
  
  <script>preset_change("preset-1");</script>
  
  
  <script>font_change("Public-Sans");</script>
  
</body>
<!-- [Body] end -->

</html>