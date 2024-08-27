@if (Session::has('error'))
<div class="d-flex align-items-center gap-1">
    <span class="material-symbols-rounded text-danger">error</span>
    <span class="text-danger"> {{ Session::get('error') }}</span>
</div>
@endif

@if (Session::has('success'))
<div class="d-flex align-items-center gap-1">
    <span class="material-symbols-rounded text-success">check-circle</span>
    <span class="text-success"> {{ Session::get('success') }}</span>
</div>
@endif