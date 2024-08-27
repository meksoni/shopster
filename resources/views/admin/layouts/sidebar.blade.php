
<aside class="page-sidebar">
  <div class="h-100 flex-column d-flex justify-content-start">

    <div class="aside-logo d-flex align-items-center flex-shrink-0 justify-content-start px-3 position-relative">
      <a href="{{ route('admin.dashboard') }}" class="d-block">
        <div class="d-flex align-items-center flex-no-wrap text-truncate">
          <!--Logo-icon-->
          <span class="sidebar-icon d-flex align-items-center justify-content-center fs-4 lh-1 text-white rounded-3 bg-primary fw-bolder">
            <img src="{{asset('storage/' . $store->cp_logo) }}" class="position-relative w-50"> 
          </span>
          <span class="sidebar-text">
            <!--Sidebar-text-->
            <span class="sidebar-text fs-3 fw-normal">
              {{ $store->cp_name }}
            </span>
          </span>
        </div>
      </a>
    </div>
    <!--Sidebar-Menu-->
    <div class="aside-menu overflow-hidden my-auto" data-simplebar>
      <nav class="flex-grow-1 " id="page-navbar">
        <!--:Sidebar nav-->
        <ul class="nav  flex-column collapse-group collapse d-flex">
          <li class="nav-item sidebar-title text-truncate opacity-50 small">
            <i class="bi bi-three-dots"></i>
            <span class="sidebar-text">Business eCommerce</span>
          </li>
          <li class="nav-item">
            <a  href="{{ route('admin.dashboard')}}" class="nav-link d-flex align-items-center text-truncate {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
              <span class="sidebar-icon">
                <span class="material-symbols-rounded">
                  monitoring
                  </span>
              </span>
              <!--Sidebar nav text-->
              <span class="sidebar-text">Kontrolna tabla</span>
            </a>
          </li>
          
          <li class="nav-item">
              <a data-bs-toggle="collapse"
                 class="nav-link d-flex align-items-center text-truncate {{ Request::is('products*') || Request::is('categories*') || Request::is('sub-categories*') || Request::is('sub-sub-categories*') || Request::is('brands*') ? 'active' : '' }}"
                 aria-expanded="{{ Request::is('products*') || Request::is('categories*') || Request::is('sub-categories*') || Request::is('sub-sub-categories*') || Request::is('brands*') ? 'true' : 'false' }}"
                 href="#prodavnica">
                  <span class="sidebar-icon">
                      <span class="material-symbols-rounded">store</span>
                  </span>
                  <!--Sidebar nav text-->
                  <span class="sidebar-text">Prodavnica</span>
              </a>
              <ul id="prodavnica" class="sidebar-dropdown list-unstyled collapse {{ Request::is('products*') || Request::is('categories*') || Request::is('sub-categories*') || Request::is('sub-sub-categories*') || Request::is('brands*') ? 'show' : '' }}">
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ Request::routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">Proizvodi</a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ Request::routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">Kategorije</a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ Request::routeIs('sub-categories.index') ? 'active' : '' }}" href="{{ route('sub-categories.index') }}">Pod Kategorije</a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link {{ Request::routeIs('sub-sub-categories.index') ? 'active' : '' }}" href="{{ route('sub-sub-categories.index') }}">Pod Pod Kategorije</a>
                </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ Request::routeIs('brands.index') ? 'active' : '' }}" href="{{ route('brands.index') }}">Brendovi</a>
                  </li>
              </ul>
          </li>

          
          
          

      
          <li class="nav-item">
            <a href="{{ route('orders.index')}}" class="nav-link d-flex align-items-center text-truncate {{ Request::routeIs('orders.index') ? 'active' : '' }}">
              <span class="sidebar-icon">
                <span class="material-symbols-rounded">
                  mail
                  </span>
              </span>
              <!--Sidebar nav text-->
              <span class="sidebar-text">
                Porudžbine 
                @if(isset($newOrdersCount) && $newOrdersCount > 0)
                    <span class="badge rounded-pill bg-primary small lh-1 ms-3">{{ $newOrdersCount }}</span>
                @endif
              </span>
            </a>
          </li>

          {{-- <li class="nav-item">
            <a href="#" class="nav-link d-flex align-items-center text-truncate {{ Request::routeIs('') ? 'active' : '' }}">
              <span class="sidebar-icon">
                <span class="material-symbols-rounded">
                  groups
                  </span>
              </span>
              <!--Sidebar nav text-->
              <span class="sidebar-text">Kupci</span>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link d-flex align-items-center text-truncate {{ Request::routeIs('') ? 'active' : '' }}">
              <span class="sidebar-icon">
                <span class="material-symbols-rounded">
                  import_contacts
                  </span>
              </span>
              <!--Sidebar nav text-->
              <span class="sidebar-text">Imenik</span>
            </a>
          </li> --}}

        </ul>
      </nav>
      
    </div>
  </div>
</aside>

<!--///Sidebar close button for 991px or below devices///-->
<div class="sidebar-close d-lg-none">
<a href="#"></a>
</div>
<!--///.Sidebar close end///-->
