@extends('admin.layouts.app')


@section('content')


<div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid position-relative px-0 ">

        <form method="post" action=""  id="productForm" name="productForm">
            <div class="row ">
                <div class="col-lg-9 mb-3">
                    <!--Card-->
                    <div class="card mb-2">
                        <div class="card-body">
    
                            <div class="row">
                                <h3>Generalne informacije</h3>
                                <hr />

                                <div class="col-12 col-md-5 mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="title">Naziv proizvoda</label>
                                        <input type="text" name="title"  class="form-control" id="title" value="{{$product->title}}">
                                        <p class="js-error"></p>
                                    </div>
                                </div>
                                {{-- ? Input with READ ONLY , automating cutting SLUG from NAME, if you want to let User change Slug - just remove readonly from input :) --}}
                                <div class="col-12 col-md-7 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >{{ config('app.url') }}/products/</span>
                                        <input type="text" readonly name="slug" class="form-control text-lowercase" id="slug" value="{{ $product->slug }}" >
                                        <p class="js-error"></p>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-12 col-md-12 mb-10">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="short_description" class="form-label">Kratak opis proizvoda</label>
                                    <small id="remaining-characters" class="text-danger">255</small>
                                </div>
                                
                                <textarea name="short_description" class="form-control" maxlength="255" id="editor">{{$product->short_description}}</textarea>
                            </div>

                            <h3>Fotografije</h3>
                                <hr />
                                <div class="col-12 col-md-12 mb-10">
                                    <label for="updateProductImage" class="form-label">Ubacite fotografiju</label>
                                    <input type="hidden" id="product_id" value="{{ $product->id }}">
                                    <div id="updateProductImage" class="dropzone border rounded-3 dz-clickable">
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
                                
                                <div class="row" id="product-gallery">
                                    @if ($productImages->isNotEmpty()) 
                                        @foreach ($productImages as $image)
                                        <div class="col-md-3 mb-10" id="image-row-{{ $image->id }}">
                                            <div class="card">
                                                <input type="hidden" name="image_array[]" value="{{ $image->id }}" />
                                                <img src="{{ asset('uploads/product/large/'.$image->image) }}" class="card-img-top" alt="" />
                                                <div class="card-body">
                                                    <a href="javascript:void(0)" data-id="{{ $image->id }}" class="btn btn-danger delete-image">Delete</a>    
                                                </div>    
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>

                            <div class="row mb-5">
                                <h3>Specifikacije</h3>
                                <div id="specifications-container">
                                    @foreach($specifications as $index => $specification)
                                    <div class="specification-item row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="specifications[{{ $index }}][name]">Naziv specifikacije</label>
                                            <input type="text" name="specifications[{{ $index }}][name]" value="{{ $specification->name }}" class="form-control" placeholder="Naziv">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label" for="specifications[{{ $index }}][value]">Naziv vrednosti</label>
                                            <input type="text" name="specifications[{{ $index }}][value]" value="{{ $specification->value }}" class="form-control" placeholder="Vrednost">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-specification">Obriši</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-primary" id="add-specification">Dodaj specifikaciju</button>
                            </div>

                            <div class="row mb-8">
                                <h3>Cena / Akcije</h3>
                                <hr/>
                                <div class="col-12 col-md-4 mb-3">       
                                    <label for="invisible_price" class="form-label">Osnovna Cena</label>
                                    <input id="invisible_price" name="invisible_price" min="0" placeholder="0.00" class="form-control">
                                    <p class="js-error"></p>
                                </div>
    
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label" for="vat_rate">PDV stopa</label>
                                    <select class="form-select" id="vat_rate" name="vat_rate">
                                        <option value="" disabled>Izaberite PDV stopu</option>
                                        <option value="0" {{ $product->vat_rate  == 0 ? 'selected' : '' }}>0%</option>
                                        <option value="10" {{ $product->vat_rate == 10 ? 'selected' : '' }}>10%</option>
                                        <option value="20" {{ $product->vat_rate == 20 ? 'selected' : '' }}>20%</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="price" class="form-label">Cena sa PDV</label>
                                    <input id="price" name="price" value="{{ $product->price}}" readonly   class="form-control" oninput="calculateDiscountedPrice()">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="discount_percentage">Procenat popusta (%):</label>
                                    <input type="number" value="{{$product->discount_percentage}}" class="form-control" id="discount_percentage" name="discount_percentage" step="1" oninput="calculateDiscountedPrice()">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="discount_start_date">Datum početka akcije:</label>
                                    <input type="date" value="{{$product->discount_start_date}}" class="form-control" name="discount_start_date" onchange="setDefaultEndDate()" id="discount_start_date">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="discount_end_date">Datum završetka akcije:</label>
                                    <input type="date" value="{{$product->discount_end_date}}" class="form-control" id="discount_end_date" name="discount_end_date">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="discount_price">Cena sa popustom:</label>
                                    <input type="text" value="{{$product->discount_price}}" class="form-control" id="discount_price" name="discount_price" step="1" readonly >
                                </div>
                            </div>

                            <div class="row mb-10">
                                <h3>Inventar</h3>
                                <hr/>
                                <div class="col-12 col-md-6 mb-3">       
                                    <label for="sku" class="form-label">Šifra</label>
                                    <input id="sku" name="sku" class="form-control" value="{{$product->sku}}">
                                    <p class="js-error"></p>
                                </div>
    
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="unit_measure">Jedinica mere</label>
                                    <select class="form-select" id="unit_measure" name="unit_measure">
                                        @foreach($unitMeasures as $measure)
                                        <option value="{{ $measure }}" {{ $product->unit_measure == $measure ? 'selected' : '' }}>
                                            {{ $measure }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mb-3 ">
                                    <label for="barcode" class="form-label">Barkod</label>
                                    <input id="barcode" name="barcode" value="{{$product->barcode}}" class="form-control">
                                </div>

                                <div class="row align-items-end">
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-check ">
                                            <label class="form-label" for="track_quantity" >
                                                Da li želite da pratite količinu?
                                            </label>
                                            <input type="hidden" name="track_quantity" value="No">
                                            <input {{($product->track_quantity == 'Yes') ? 'checked' : ''}} class="form-check-input" name="track_quantity" value="Yes" checked type="checkbox" id="track_quantity">
                                        </div>
                                    </div>
    
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="quantity" class="form-label">Količina</label>
                                        <input id="quantity" name="quantity" value="{{$product->quantity}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-10">
                                <h3>Kategorizacija</h3>
                                <hr/>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="category">Kategorija</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="" disabled selected>Izaberite kategoriju</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ ($product->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="js-error"></p>
                                </div>
    
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="sub_category">Podkategorija</label>
                                    <select class="form-select" id="sub_category" name="sub_category">
                                        <option value="">Izaberite podkategoriju</option>
                                        @if ($subCategories->isNotEmpty())
                                            @foreach ($subCategories as $subCategory)
                                                <option {{ ($product->sub_category_id == $subCategory->id) ? 'selected' : '' }} value="{{ $subCategory->id }}">{{$subCategory->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="sub_sub_category">Podkategorija</label>
                                    <select class="form-select" id="sub_sub_category" name="sub_sub_category">
                                        <option value="">Izaberite PodPod kategoriju</option>
                                        @if ($subSubCategories->isNotEmpty())
                                            @foreach ($subSubCategories as $subSubCategory)
                                                <option {{ ($product->sub_sub_category_id == $subSubCategory->id) ? 'selected' : '' }} value="{{ $subSubCategory->id }}">{{$subSubCategory->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="brand">Brend</label>
                                    <select class="form-select" id="brand" name="brand">
                                        <option value="" disabled selected>Izaberite brend</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option {{ ($product->brand_id == $brand->id) ? 'selected' : '' }} value="{{ $brand->id }}">{{$brand->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>               
                </div>
                    
                <div class="col-lg-3 ">
                    <div class="card mb-2">
                        <div class="card-body">  
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option {{ ($product->status == 0) ? 'selected' : '' }} value="0" >Neaktivno</option>        
                                <option {{ ($product->status == 1) ? 'selected' : '' }} value="1" >Aktivno</option>        
                            </select>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">  
                            <label class="form-label" for="is_featured">Istakni proizvod (na početnoj strani)</label>
                            <select class="form-select" id="is_featured" name="is_featured">
                                <option {{ ($product->is_featured == 'Yes') ? 'selected' : '' }} value="Yes">Da</option>        
                                <option {{ ($product->is_featured == 'No') ? 'selected' : '' }} value="No">Ne</option>        
                            </select>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">
                            <p>Naziv: <span class="fw-semibold" id="display_title"></span></p>
                            <p>URL: <a href="" onclick="return false;" target="_blank" class="text-primary" id="display_url"></a></p>
                            <p>Ukupna cena: <span class="fw-semibold" id="display_price">0</span></p>
                            <hr class="my-4">
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">Sačuvaj</button>
                                <a href="{{ route('products.index')}}"  class="btn btn-sm btn-danger">Poništi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection

@section('customJS')


<script>

window.routes = {
    UpdateImageRoute: "{{ route('product-images.update') }}",
    DeleteImageRoute: "{{ route('product-images.destroy') }}"
    // Ostale rute ovde po potrebi
};
window.productId = {{ $product->id }};

// Generate Slug from Title
document.getElementById("title").addEventListener("input", function () {
    var title = this.value;
    var slugInput = document.getElementById("slug");

    clearTimeout(this.timeout);
    this.timeout = setTimeout(function () {
        var generatedSlug = generateSlug(title);
        slugInput.value = generatedSlug;
    }, 1000); // 1000ms = 1 sekunda
});

function generateSlug(title) {
    return title
        .trim() // Remove leading and trailing whitespaces
        .toLowerCase() // Convert to lowercase
        .replace(/đ/g, 'dj')       // Zamena đ sa dj
        .replace(/č/g, 'c')        // Zamena č sa c
        .replace(/ć/g, 'c')        // Zamena ć sa c
        .replace(/š/g, 's')        // Zamena š sa s
        .replace(/ž/g, 'z')        // Zamena ž sa z
        .replace(/\s+/g, "-") // Replace spaces with hyphens
        .replace(/[^a-z0-9-]/g, ""); // Remove non-alphanumeric characters except hyphens
}

function calculateDiscountedPrice() {
    const priceInput = document.getElementById('price');
    const discountPercentageInput = document.getElementById('discount_percentage');
    const discountedPriceInput = document.getElementById('discount_price');

    const price = parseFloat(priceInput.value);
    const discountPercentage = parseFloat(discountPercentageInput.value);

    if (!isNaN(price) && !isNaN(discountPercentage)) {
        const discountedPrice = price - (price * discountPercentage / 100);
        discountedPriceInput.value = discountedPrice.toFixed(2);
    } else {
        discountedPriceInput.value = '';
    }
}

function setDefaultEndDate() {
    const startDateInput = document.getElementById('discount_start_date');
    const endDateInput = document.getElementById('discount_end_date');

    // Postavi današnji datum ako polje za start datum nije popunjeno
    if (!startDateInput.value) {
        const today = new Date();
        const day = today.getDate().toString().padStart(2, '0');
        const month = (today.getMonth() + 1).toString().padStart(2, '0');
        const formattedToday = `${today.getFullYear()}-${month}-${day}`;

        startDateInput.value = formattedToday;
    }

    // Izračunaj i postavi default završni datum dodavanjem 10 dana na start datum
    if (startDateInput.value) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(startDate.getTime());
        endDate.setDate(startDate.getDate() + 10);

        const day = endDate.getDate().toString().padStart(2, '0');
        const month = (endDate.getMonth() + 1).toString().padStart(2, '0');
        const formattedEndDate = `${endDate.getFullYear()}-${month}-${day}`;

        endDateInput.value = formattedEndDate;
    }
}


document.addEventListener("DOMContentLoaded", function() {


    function initializeInvisiblePrice() {
    var price = parseFloat($("#price").val().replace(",", "."));
    var vatRate = parseFloat($("#vat_rate").val());
    var basePrice = price / (1 + (vatRate / 100));
    $("#invisible_price").val(Math.floor(basePrice));
}

// Funkcija za izračunavanje cene sa PDV-om
function calculateVAT() {
    var basePrice = parseFloat($("#invisible_price").val());
    var vatRate = parseFloat($("#vat_rate").val());
    var vatMultiplier = 1 + (vatRate / 100);
    var vatPrice = basePrice * vatMultiplier;
    $("#price").val(vatPrice.toFixed(2)); // Ova funkcija koristi tačku kao decimalni separator
}

// Pozovi funkciju prilikom učitavanja stranice
initializeInvisiblePrice();

// Postavi event listenere za promene u polju invisible_price i vat_rate
$("#invisible_price").on("input", function() {
    // Proveri da li je vrednost validna pre nego što pozove calculateVAT
    var value = $(this).val().replace(",", ".");
    if (!isNaN(parseFloat(value)) && isFinite(value)) {
        calculateVAT();
    }
});

$("#vat_rate").on("change", function() {
    calculateVAT();
    initializeInvisiblePrice();
});

    $('textarea').on("input", function() {
      var maxlength = $(this).attr("maxlength");
      var currentLength = $(this).val().length;
    
      if (currentLength > maxlength) {
        console.log("Dostigli ste maksimalan broj karaktera");
        return;
      }

      var finalLength = maxlength - currentLength;
    
      $("#remaining-characters").text(finalLength);
    });


    

    $("#productForm").submit(function (event) {
    event.preventDefault();

    var description = $(".ql-editor").html();
    var formArray = $(this).serializeArray();
    formArray.push({ name: "description", value: description });

    $.ajax({
        url: '{{route("products.update",$product->id)}}',
        type: "put",
        data: formArray,
        dataType: "json",
        success: function (response) {
            if (response["status"] == true) {

                $(".js-error").removeClass("invalid-feedback").html("");
                $("input[type='text'], select").removeClass("is-invalid");

                window.location.href="{{ route('products.index') }}"
            } else {
                var errors = response["errors"];

                $(".js-error").removeClass("invalid-feedback").html("");
                $("input[type='text'], select").removeClass("is-invalid");

                $.each(errors, function (key, value) {
                    $(`#${key}`)
                        .addClass("is-invalid")
                        .siblings("p")
                        .addClass("invalid-feedback")
                        .html(value);
                });
            }
        },
        error: function () {
            console.log("Something went wrong");
        },
    });
});





document.getElementById('add-specification').addEventListener('click', function () {
    const index = document.querySelectorAll('.specification-item').length;
    const specificationItem = document.createElement('div');
    specificationItem.classList.add('specification-item', 'row', 'mb-3');
    specificationItem.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="specifications[${index}][name]" class="form-control" placeholder="Naziv">
        </div>
        <div class="col-md-5">
            <input type="text" name="specifications[${index}][value]" class="form-control" placeholder="Vrednost">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-specification">Ukloni</button>
        </div>
    `;
    document.getElementById('specifications-container').appendChild(specificationItem);
});

document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('remove-specification')) {
        e.target.closest('.specification-item').remove();
    }
});


$("#category").change(function () {
    var category_id = $(this).val();
    $.ajax({
        url: '{{ route("product-routes.index") }}',
        type: "get",
        data: { category_id: category_id },
        dataType: "json",
        success: function (response) {
            $("#sub_category").find("option").not(":first").remove();
            $("#sub_sub_category").find("option").not(":first").remove();
            $.each(response["subCategories"], function (key, item) {
                $("#sub_category").append(
                    `<option value='${item.id}''>${item.name}</option>`
                );
            });
        },
        error: function () {
            console.log("Something went wrong");
        },
    });
});

$("#sub_category").change(function () {
    var sub_category_id = $(this).val();
    $.ajax({
        url: '{{ route("product-routes.index") }}',
        type: "get",
        data: { sub_category_id: sub_category_id },
        dataType: "json",
        success: function (response) {
            $("#sub_sub_category").find("option").not(":first").remove();
            $.each(response["subSubCategories"], function (key, item) {
                $("#sub_sub_category").append(
                    `<option value='${item.id}''>${item.name}</option>`
                );
            });
        },
        error: function () {
            console.log("Something went wrong");
        },
    });
});


})

</script>
@endsection