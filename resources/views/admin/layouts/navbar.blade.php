<!--//page-header//-->
<header class="navbar transition-base border-bottom mb-3 px-3 px-lg-6 px-3 px-lg-6 align-items-center page-header navbar-expand navbar-light">
  <a href="" class="navbar-brand d-block d-lg-none">
    <div class="d-flex align-items-center flex-no-wrap text-truncate">
      <!--Sidebar-icon-->
      <span class="sidebar-icon bg-primary rounded-3 size-40 fw-bolder text-white">
          <img src="{{asset('storage/' . $store->cp_logo) }}" class="position-relative w-50"> 
      </span>
    </div>
  </a>
  <ul class="navbar-nav d-flex align-items-center h-100">
    <li class="nav-item d-none d-lg-flex flex-column h-100 me-1 align-items-center justify-content-center" data-tippy-placement="bottom-start" data-tippy-content="Toggle Sidebar">
      <a href="javascript:void(0)"
        class="sidebar-trigger nav-link size-40 d-flex align-items-center justify-content-center p-0">
        <span class="material-symbols-rounded fs-4">
          menu_open
          </span>
      </a>
    </li>

    <li class="nav-item d-none d-md-flex flex-md-column me-2 h-100 justify-content-md-center dropdown">
      <a href="#" data-bs-toggle="dropdown"
        class="nav-link size-40 d-flex align-items-center justify-content-center p-0">
        <span class="material-symbols-rounded fs-4">
          apps
          </span>
      </a>
      <div class="dropdown-menu overflow-hidden dropdown-menu-sm p-0 mt-0">
        <!--Apps list-->
        <div class="list-group list-group-flush">

          <div class="list-group-item d-flex align-items-center justify-content-between px-3">
            <h6 class="dropdown-header ps-0">NeoDigital | Digital Web Solutions</h6>
            
          </div>
          <!--App item-->
          <a href="{{ route('problems.show')}}" class="list-group-item px-4 py-3 list-group-item-action d-flex align-items-center">
            <!--App logo-->
            <img src="{{ asset('storage/icons/svg-icons/help.svg')}}" class="width-30 w-auto flex-shrink-0 me-4" alt="">
            <div class="flex-grow-1">
              <h6 class="mb-0">Prijava problema</h6>
              <small>Prijava problema sa radom aplikacije.</small>
            </div>
          </a>

          <!--App item-->
          <a href="#!" class="list-group-item px-4 py-3 list-group-item-action d-flex align-items-center">
            <!--App logo-->
            <img src="{{ asset('storage/icons/svg-icons/info.svg') }}" class="width-30 w-auto  flex-shrink-0 me-4" alt="">

            <div class="flex-grow-1">
              <h6 class="mb-0">Uputstva</h6>
              <small>(U izradi)</small>
            </div>
          </a>

          <!--App item-->
          {{-- <a href="#!" class="list-group-item px-4 py-3 list-group-item-action d-flex align-items-center">
            <!--App logo-->
            <img src="" class="width-30 w-auto flex-shrink-0 me-4" alt="">
            <div class="flex-grow-1">
              <h6 class="mb-0">NeoDigital</h6>
              <small>Posetite zvaničnu stranicu proizvođača aplikacije</small>
            </div>
          </a> --}}
        </div>
      </div>
    </li>
    
    @can('super-admin-permission')
    <li class="nav-item d-none d-lg-flex flex-column h-100 me-1 align-items-center justify-content-center">
      <form method="POST" action="{{ route('optimize.clear') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary">
          Očistite Keš Memoriju
        </button>
      </form>
    </li>
    @endcan

  
    
  </ul>
  
  <ul class="navbar-nav ms-auto d-flex align-items-center h-100">
  
    <li class="nav-item dropdown d-flex align-items-center justify-content-center flex-column h-100 mx-1 mx-md-2">
      <a href="#"
        class="nav-link p-0 position-relative size-40 d-flex align-items-center justify-content-center"
        aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
        <span class="material-symbols-rounded fs-4">
          message
        </span>
        <span id="notificationIndicator"
          class="size-5 rounded-circle d-flex align-items-center justify-content-center position-absolute end-0 top-0 mt-2 me-1 bg-danger small"></span>
      </a>
    
      <div class="dropdown-menu mt-0 p-0 overflow-hidden dropdown-menu-end dropdown-menu-sm">
        <!--notification header-->
        <div class="py-3 px-4 bg-primary text-white d-flex align-items-center">
          <h5 class="me-3 mb-0 flex-grow-1">Notifikacije</h5>
          {{-- <div class="flex-shrink-0">
            <a href="#!" class="btn btn-white btn-sm">Pogledaj sve</a>
          </div> --}}
        </div>
        <div style="height:290px" data-simplebar>
          <div class="list-group list-group-flush mb-0" id="notificationList">
            <!-- Dinamički generisane notifikacije -->
          </div>
        </div>
      </div>
    </li>

    <li class="nav-item dropdown d-flex align-items-center justify-content-center flex-column h-100">
      <a href="#offcanvas_user"
        class="nav-link height-40 px-2 d-flex align-items-center justify-content-center"
        aria-expanded="false" data-bs-toggle="offcanvas">
        <div class="d-flex align-items-center">

          <!--Avatar with status-->
          <div class="avatar-status status-online me-sm-2 avatar xs">
            @if (Storage::exists('public/user_images/user_' . $user->id . '.png'))
            <img src="{{ asset('storage/user_images/user_' . $user->id . '.png') }}" class="rounded-circle img-fluid">
            @else
            <img src="{{asset('storage/icons/user.png')}}" class="rounded-circle img-fluid" alt="">
            @endif
          </div>
          
        <span class="d-none d-md-inline-block">{{ $user->username }}</span>
        </div>
      </a>
    </li>
    <li
      class="nav-item dropdown ms-1 d-flex d-lg-none align-items-center justify-content-center flex-column h-100">
      <a href="javascript:void(0)"
        class="nav-link sidebar-trigger-lg-down size-40 p-0 d-flex align-items-center justify-content-center">
       <span class="material-symbols-rounded fs-3 align-middle">menu</span>
      </a>
    </li>
  </ul>
</header>
<!--Main Header End-->

<!--:User offcanvas menu:-->
<div class="offcanvas offcanvas-end border-0" style="--bs-offcanvas-width: 290px;" id="offcanvas_user">
<div class="offcanvas-body p-0">
  <!--User meta-->
  <div class="position-relative overflow-hidden offcanvas-header px-3 pt-6 pb-10 bg-body-secondary">
    <!--Divider-->
    <svg style="transform: rotate(-180deg);color:var(--bs-offcanvas-bg)" preserveAspectRatio="none"
      class="position-absolute start-0 bottom-0 w-100" fill="currentColor" height="24" viewBox="0 0 1200 120"
      xmlns="http://www.w3.org/2000/svg">
      <path
        d="M0 0v46.29c47.79 22.2 103.59 32.17 158 28 70.36-5.37 136.33-33.31 206.8-37.5 73.84-4.36 147.54 16.88 218.2 35.26 69.27 18 138.3 24.88 209.4 13.08 36.15-6 69.85-17.84 104.45-29.34C989.49 25 1113-14.29 1200 52.47V0z"
        opacity=".25" />
      <path
        d="M0 0v15.81c13 21.11 27.64 41.05 47.69 56.24C99.41 111.27 165 111 224.58 91.58c31.15-10.15 60.09-26.07 89.67-39.8 40.92-19 84.73-46 130.83-49.67 36.26-2.85 70.9 9.42 98.6 31.56 31.77 25.39 62.32 62 103.63 73 40.44 10.79 81.35-6.69 119.13-24.28s75.16-39 116.92-43.05c59.73-5.85 113.28 22.88 168.9 38.84 30.2 8.66 59 6.17 87.09-7.5 22.43-10.89 48-26.93 60.65-49.24V0z"
        opacity=".5" />
      <path
        d="M0 0v5.63C149.93 59 314.09 71.32 475.83 42.57c43-7.64 84.23-20.12 127.61-26.46 59-8.63 112.48 12.24 165.56 35.4C827.93 77.22 886 95.24 951.2 90c86.53-7 172.46-45.71 248.8-84.81V0z" />
    </svg>
    <div class="position-relative flex-grow-1">
      <div>
        <div class="flex-shrink-0 ">
          @if (Storage::exists('public/user_images/user_' . $user->id . '.png'))
          <img src="{{ asset('storage/user_images/user_' . $user->id . '.png') }}" class="rounded-circle shadow width-50 d-block mx-auto img-fluid" alt="">
          @else
          <img src="{{asset('storage/icons/user.png')}}" class="rounded-circle shadow width-50 d-block mx-auto img-fluid" alt="">
          @endif
        </div>
        <div class="text-center pt-4">
          <h5 class="mb-1">{{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}</h5>
          <p class="text-body-tertiary mb-0 lh-1">{{ Auth::guard('admin')->user()->email }}</p>
        </div>
      </div>
    </div>
    <button type="button" class="btn btn-sm px-2 btn-white position-absolute end-0 top-0 me-2 mt-2" data-bs-dismiss="offcanvas">
      <span class="material-symbols-rounded fs-5 align-middle">close</span>
    </button>
  </div>
  <div class="list-group rounded-0 px-3 py-4 gap-1">
    <a href="{{ route('account.settings')}}" class="list-group-item-action rounded px-2 py-1 d-flex align-items-center">
      <span class="material-symbols-rounded align-middle me-2 size-30 fs-4 d-flex align-items-center justify-content-center text-primary">
      manage_accounts
      </span>
    <span class="flex-grow-1">Podešavanje Naloga</span>
    </a>

    @can('super-admin-permission')
    <a href="{{ route('admin.users') }}" class="list-group-item-action rounded px-2 py-1 d-flex align-items-center">
        <span class="material-symbols-rounded align-middle me-2 size-30 fs-4 d-flex align-items-center justify-content-center text-primary">
        groups
        </span>
        <span class="flex-grow-1">Korisnici</span>
    </a>
    @endcan

    @can('super-admin-permission')
    <a href="{{ route('store.edit') }}" class="list-group-item-action rounded px-2 py-1 d-flex align-items-center">
        <span class="material-symbols-rounded align-middle me-2 size-30 fs-4 d-flex align-items-center justify-content-center text-primary">
        settings
        </span>
        <span class="flex-grow-1">Podešavanje Prodavnice</span>
    </a>
    @endcan


  </div>
</div>
  <div class="offcanvas-footer border-top rounded-0 list-group p-3">
    <a href="{{ route('admin.logout') }}" class="list-group-item-action rounded px-2 py-1 d-flex align-items-center">
      <span class="material-symbols-rounded align-middle me-2 size-30 fs-4 d-flex align-items-center justify-content-center text-primary">
      logout
      </span>
    <span class="flex-grow-1">Odjavi se</span>
    </a>
  </div>
</div>