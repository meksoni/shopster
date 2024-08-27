@extends('admin.layouts.app')


@section('content')

<div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid px-0 h-100">

        <div class="row">
            <div class="col-lg-12">
                <!--Card-->
                <div class="card mb-2">
                    <div class="card-body">

                        <form method="post" action=""  id="brandForm" name="brandForm">

                            <div class="row">
                                

                                <div class="col-12 col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Brands Name</label>
                                        <input type="text" name="name"  class="form-control" id="name">
                                        <p></p>
                                    </div>
                                </div>

                                {{-- ? Input with READ ONLY , automating cutting SLUG from NAME, if you want to let User change Slug - just remove readonly from input :) --}}
                                <div class="col-12 col-md-8 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >{{ config('app.url') }}/brands/</span>
                                        <input type="text" readonly name="slug" class="form-control text-lowercase" id="slug" >
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
                                </div>
                            </div>
                            
                            <div class="row">      
                                
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">Create</button>
                                <a href="{{ route('brands.index')}}"  class="btn btn-sm btn-danger">Cancel</a>
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
        $("#brandForm").submit(function(event){
    event.preventDefault();

    var element = $("#brandForm");
    $("button[type=submit]").prop('disabled', true);

    $.ajax({
        url: '{{ route("brands.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);
            
            if (response['status'] == true) {

                window.location.href='{{ route("brands.index")}}'

                $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html(''); 

                $("#slug").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html('');

            } else {

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

                // if(errors['category']) {
                //     $("#category").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback').html(errors['category']);
                // } else {
                //     $("#category").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html('');
                // }
            }

        }, error: function(jqXHR, exception) {
            console.log("Something went wrong")
        }
    })
});
    })
</script>
@endsection