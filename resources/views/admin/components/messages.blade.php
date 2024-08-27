@if (Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">

    <span class="material-symbols-rounded align-middle me-2">error</span>
    {!! Session::get('error') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


@if (Session::has('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">

    <span class="material-symbols-rounded align-middle me-2">check_circle</span>
    {!! Session::get('success') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (Session::has('warning'))
<div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
 
  <span class="material-symbols-rounded align-middle me-2">check_circle</span>
   {!! Session::get('warning') !!}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif