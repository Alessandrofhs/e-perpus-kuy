<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard') }}" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="{{ asset('templates/dist/assets/images/logo-e-perpus-kuy-hori.png') }}" class="img-fluid" style="max-height:80px;" alt="logo">
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item">
          <a href="{{ route('dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        @if(Auth::user()->role == 'admin')
        <li class="pc-item pc-caption">
          <label>Master Data</label>
        <i class="ti ti-dashboard"></i>
        </li>
          <li class="pc-item">
            <a href="{{ route('users.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-user"></i></span>
              <span class="pc-mtext">Pengguna</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('books.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-book"></i></span>
              <span class="pc-mtext">Buku</span>
            </a>
          </li>
        @endif

        <li class="pc-item pc-caption">
          <label>Transaction</label>
          <i class="ti ti-news"></i>
        </li>
        <li class="pc-item">
          <a href="{{ route('loans.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-lock"></i></span>
            <span class="pc-mtext">Peminjaman</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('returns.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-lock"></i></span>
            <span class="pc-mtext">Pengembalian</span>
          </a>
        </li>
      </ul> 
    </div>
  </div>
</nav>