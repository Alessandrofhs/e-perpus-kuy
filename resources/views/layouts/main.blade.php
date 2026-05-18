
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>@yield('title' , 'Admin')</title>
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
{{-- Datatable --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
 <!-- [ Sidebar Menu ] start -->
@include('partials.sidebar')
<!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
@include('partials.header')
<!-- [ Header ] end -->

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    @yield('content')
  </div>
  <!-- [ Main Content ] end -->
  @include('partials.footer')

  <!-- [Page Specific JS] start -->
  <script src="{{ asset('templates/dist/assets/js/plugins/apexcharts.min.js') }}"></script>
  @stack('page-scripts')
  <!-- [Page Specific JS] end -->
  <!-- Required Js -->
  <script src="{{ asset('templates/dist/assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('templates/dist/assets/js/plugins/feather.min.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  @yield('scripts')

  
  
  
  
  <script>layout_change('light');</script>
  
  
  
  
  <script>change_box_container('false');</script>
  
  
  
  <script>layout_rtl_change('false');</script>
  
  
  <script>preset_change("preset-1");</script>
  
  
  <script>font_change("Public-Sans");</script>
  
    

</body>
<!-- [Body] end -->

</html>