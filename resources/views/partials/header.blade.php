<header class="pc-header">
  <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
    <div class="me-auto pc-mob-drp">
    <ul class="list-unstyled">
        <!-- ======= Menu collapse Icon ===== -->
        <li class="pc-h-item pc-sidebar-collapse">
        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
            <i class="ti ti-menu-2"></i>
        </a>
        </li>
        <li class="pc-h-item pc-sidebar-popup">
        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
            <i class="ti ti-menu-2"></i>
        </a>
        </li>
            </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
            <ul class="list-unstyled">
        <li class="dropdown pc-h-item">
        <a class="pc-head-link dropdown-toggle arrow-none me-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
        id="notifToggle">
            <i class="ti ti-bell"></i>
            {{-- Badge jumlah notif belum dibaca --}}
            <span class="badge bg-danger rounded-pill position-absolute"
                id="notifBadge"
                style="display:none; font-size:9px; top:4px; right:4px;">
            </span>
        </a>

            <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                {{-- Header --}}
                <div class="dropdown-header d-flex align-items-center justify-content-between">
                    <h5 class="m-0">Notifikasi</h5>
                    <a href="#" id="readAllNotif" class="text-muted" style="font-size:12px;">
                        Tandai semua dibaca
                    </a>
                </div>

                <div class="dropdown-divider"></div>

                {{-- List Notifikasi --}}
                <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                    style="max-height: calc(100vh - 215px)">
                    <div class="list-group list-group-flush w-100" id="notifList">
                        {{-- Diisi oleh JS --}}
                    </div>

                    {{-- Kosong --}}
                    <div id="notifEmpty" class="text-center py-4" style="display:none;">
                        <i class="ti ti-bell-off text-muted" style="font-size:32px;"></i>
                        <p class="text-muted mt-2 mb-0" style="font-size:13px;">Tidak ada notifikasi</p>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <div class="text-center py-2">
                    <a href="#" id="readAllNotif2" class="link-primary" style="font-size:13px;">
                        Tandai semua sudah dibaca
                    </a>
                </div>
            </div>
        </li>
        <li class="dropdown pc-h-item header-user-profile">
        <a
            class="pc-head-link dropdown-toggle arrow-none me-0"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-haspopup="false"
            data-bs-auto-close="outside"
            aria-expanded="false"
        >
            <img 
                src="{{ Auth::user()->photo 
                        ? asset('storage/' . Auth::user()->photo) 
                        : asset('templates/dist/assets/images/user/avatar-2.jpg') }}"
                alt="user-image"
                class="user-avtar rounded-circle"
                style="
                    width: 40px;
                    height: 40px;
                    object-fit: cover;
                "
            >
            <span>{{ Auth::user()->name }}</span>
        </a>
        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
            <div class="dropdown-header">
                <div class="d-flex mb-1">
                    <div class="flex-shrink-0">
                    <img 
                        src="{{ Auth::user()->photo 
                                ? asset('storage/' . Auth::user()->photo) 
                                : asset('templates/dist/assets/images/user/avatar-2.jpg') }}"
                        alt="user-image"
                        class="user-avtar rounded-circle"
                        style="
                            width: 40px;
                            height: 40px;
                            object-fit: cover;
                        "
                    >
                    </div>
                    <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                    <span>{{ Auth::user()->role }}</span>
                    </div>
                    <a href="{{ route('logout') }}"
                        class="pc-head-link bg-transparent"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                            <i class="ti ti-power text-danger"></i>

                        </a>

                        <form 
                            id="logout-form"
                            action="{{ route('logout') }}"
                            method="POST"
                            class="d-none"
                        >

                            @csrf

                        </form>
                </div>
            </div>
            <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                class="nav-link active"
                id="drp-t1"
                data-bs-toggle="tab"
                data-bs-target="#drp-tab-1"
                type="button"
                role="tab"
                aria-controls="drp-tab-1"
                aria-selected="true"
                ><i class="ti ti-user"></i> Profile</button
                >
            </li>
            </ul>
            <div class="tab-content" id="mysrpTabContent">
            <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1" tabindex="0">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                <i class="ti ti-edit-circle"></i>
                <span>Edit Profile</span>
                </a>
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                <i class="ti ti-user"></i>
                <span>View Profile</span>
                </a>
                <form id="logout-form" 
                    action="{{ route('logout') }}" 
                    method="POST" 
                    class="d-none">

                    @csrf

                </form>
                <a href="{{ route('logout') }}" class="dropdown-item"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ti ti-power"></i>
                    <span>Logout</span>
                </a>
            </div>
            
            </div>
        </div>
        </li>
    </ul>
    </div>
 </div>
</header>