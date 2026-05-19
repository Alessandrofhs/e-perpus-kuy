<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Login | E-Perpus-Kuy</title>
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
  <form action="{{ route('login.process') }}" method="POST">
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
                      <h3 class="mb-0"><b>Login</b></h3>
                      <a href="{{ route('register') }}" class="link-primary">Belum memiliki akun?</a>
                      </div>
                      <div class="form-group mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
                      </div>
                      <div class="form-group mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" class="form-control" placeholder="Password" name="password" required autocomplete="current-password">
                      </div>
                      <div class="d-flex mt-1 justify-content-between">
                      <div class="form-check">
                          <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                          <label class="form-check-label text-muted" for="customCheckc1">Biarkan saya tetap masuk?</label>
                      </div>
                          <a href="{{ route('password.request') }}" class="text-secondary f-w-400 h5">
                              lupa Password?
                          </a>
                      </div>
                      <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Login</button>
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
  
  
  
  <script>layout_change('light');</script>
  
  
  
  
  <script>change_box_container('false');</script>
  
  
  
  <script>layout_rtl_change('false');</script>
  
  
  <script>preset_change("preset-1");</script>
  
  
  <script>font_change("Public-Sans");</script>
  
    @if ($errors->any())

    <script>

        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach

    </script>

    @endif
 
</body>
<!-- [Body] end -->

</html>

              