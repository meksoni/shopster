<div class="toolbar px-3 px-lg-6 py-3">
  <div class="position-relative container-fluid px-0">
    <div class="row align-items-center position-relative">
      {{-- <div class="col-xl-8 mb-2 mb-md-0"> 
        <nav aria-label="breadcrumb" class="d-md-flex justify-content-md-start">
          <ol class="breadcrumb mb-0">
            @foreach ($breadcrumbs as $breadcrumb)
              @if (!$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a></li>
              @else
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
              @endif
            @endforeach

          </ol>
        </nav>
      </div> --}}

      <div class="col-lg-4 text-md-start">
        @switch($currentPage)
        @case('products')
            <a href="{{ route('products.create') }}" class="btn rounded-1 btn-primary d-inline-flex align-items-center">
                <span class="align-middle material-symbols-rounded fs-5 me-1">
                  add
                </span>
                Dodaj proizvod
            </a>
        @break
        @case('categories')
            <a href="{{ route('categories.create') }}" class="btn rounded-1 btn-primary d-inline-flex align-items-center">
                <span class="align-middle material-symbols-rounded fs-5 me-1">
                  add
                </span>
                Dodaj kategoriju
            </a>
        @break
        @case('sub-categories')
            <a href="{{ route('sub-categories.create') }}" class="btn rounded-1 btn-primary d-inline-flex align-items-center">
                <span class="align-middle material-symbols-rounded fs-5 me-1">
                  add
                </span>
                Dodaj Pod-kategoriju
            </a>
        @break
        @case('sub-sub-categories')
            <a href="{{ route('sub-sub-categories.create') }}" class="btn rounded-1 btn-primary d-inline-flex align-items-center">
                <span class="align-middle material-symbols-rounded fs-5 me-1">
                  add
                </span>
                Dodaj Pod-pod-kategoriju
            </a>
        @break
        @case('brands')
            <a href="{{ route('brands.create') }}" class="btn rounded-1 btn-primary d-inline-flex align-items-center">
                <span class="align-middle material-symbols-rounded fs-5 me-1">
                  add
                </span>
                Dodaj brend
            </a>
        @break
        
      @endswitch
      </div>
    </div>
  </div>
</div>