@extends('admin.layouts.app', [
    'showToolbar' => true,
])


@section('content')
<div class="container px-0">
    @include('admin.components.messages')
</div>

<!-- Content -->
<div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid px-0 position-relative">

        <div class="row">
            
            <div class="col-lg-12">
                <!--Card-->
                <div class="card mb-2">
                    <div class="card-body">

                        <form method="post" action=""  id="categoryForm" name="categoryForm">
                            
                            <div class="row">
                                <div class="col-12 col-md-5 mb-3">

                                    <div class="form-group">
                                        <label class="form-label" for="name">Naziv kategorije</label>
                                        <input type="text" value="{{ $category->name }}" name="name"  class="form-control" id="name">
                                        <p></p>
                                    </div>
                                
                                </div>

                                {{-- ? Input with READ ONLY , automating cutting SLUG from NAME, if you want to let User change Slug - just remove readonly from input :) --}}
                                <div class="col-12 col-md-7 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >/categories/</span>
                                        <input type="text" value="{{ Str::slug($category->slug) }}" readonly name="slug" class="form-control text-lowercase" id="slug" >
                                        <p></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-12 mb-3">
                                    <input type="hidden" id="image_id" name="image_id" value="">
                                    <label for="image" class="form-label">Ubaci fotografiju</label>
                                    <div id="image" class="dropzone border rounded-3 dz-clickable">
                                        <div class="dz-message needsclick">    
                                            <div class="mb-3">
                                                <span class="material-symbols-rounded align-middle">
                                                    upload
                                                    </span>
                                            </div>

                                            <h4 class="mb-0">Drop files here or click to upload.</h4>                                            
                                        </div>
                                    </div>
                                    @if(!empty($category->image))
                                    <div>
                                        <img src="{{asset('uploads/categories/thumb/'.$category->image) }}" width="150"  height="150">
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option  {{ ($category->status == 1) ? 'selected' : ''}} value="1">Aktivno</option>
                                        <option {{ ($category->status == 0) ? 'selected' : ''}} value="0">Neaktivno</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="showHome">Prikaži kategoriju na početnoj</label>
                                    <select class="form-select" id="showHome" name="showHome">
                                        <option {{ ($category->showHome == 'Yes') ? 'selected' : ''}}  value="Yes">Da</option>
                                        <option {{ ($category->showHome == 'No') ? 'selected' : ''}}  value="No">Ne</option>
                                    </select>
                                </div>

                            </div>
                            
                            <hr class="my-4">
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">Sačuvaj</button>
                                <a href="{{ route('categories.index')}}"  class="btn btn-sm btn-danger">Poništi</a>
                            </div>
                        </form>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJS')
<script>
window.routes = {
    ImageRoute: "{{ route('temp-images.create') }}",
    // Ostale rute ovde po potrebi
};

    function generateSlug(name) {
        return name.trim()         // Remove leading and trailing whitespaces
                   .toLowerCase()  // Convert to lowercase
                   .replace(/\s+/g, '-')      // Zamena razmake s crticama
                   .replace(/đ/g, 'dj')       // Zamena đ sa dj
                   .replace(/č/g, 'c')        // Zamena č sa c
                   .replace(/ć/g, 'c')        // Zamena ć sa c
                   .replace(/š/g, 's')        // Zamena š sa s
                   .replace(/ž/g, 'z')        // Zamena ž sa z
                   .replace(/[^a-z0-9-]/g, ''); // Remove non-alphanumeric characters except hyphens
    }

    // Event listener for category name input field
    document.getElementById('name').addEventListener('input', function() {
        var name = this.value;
        var slugInput = document.getElementById('slug');
        var generatedSlug = generateSlug(name);
        slugInput.value = generatedSlug;
    });

document.addEventListener("DOMContentLoaded", function() {
    $("#categoryForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $.ajax({
            url: '{{ route("categories.update",$category->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){

                if (response['status'] == true) {

                    window.location.href='{{ route("categories.index")}}'

                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html(''); 

                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html('');

                } else {

                    if (response['notFound' == true]) {
                        window.location.href="{{ route('categories.index') }}";
                    }

                    var errors = response['errors'];

                    if(errors['name']) {
                        $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html('');     
                    }
    
                    if(errors['slug']) {
                        $("#slug").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['slug']);
                    } else {
                        $("#slug").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html('');
                    }
                }

                
                

            }, error: function(jqXHR, exception) {
                console.log("Something went wrong")
            }
        })
    });
})
</script>
@endsection