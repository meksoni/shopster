@extends('shop.layouts.app')


<style>

  .image-container {
      width: 100%;
      height: 250px; /* Možete prilagoditi visinu prema vašim potrebama */
      position: relative;
      padding: 15px 0;
      box-sizing: border-box;
  }
  
  .image {
      width: 100%;
      height: 100%;
      background-size: contain; /* Osigurava da cela slika bude vidljiva */
      background-position: center;
      background-repeat: no-repeat; /* Sprečava ponavljanje slike */
      /* background-color: #f8f9fa; Dodajte pozadinsku boju ako je potrebno */
  }
  
  .brand-image {
      width: 85%;
      height: 85%;
      background-size: contain; /* Osigurava da cela slika bude vidljiva */
      background-position: center;
      background-repeat: no-repeat; /* Sprečava ponavljanje slike */
      margin:0 auto;
      /* background-color: #f8f9fa; Dodajte pozadinsku boju ako je potrebno */
  }
  
  .clamp-text {
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis; /* Ograničava tekst na 2 reda */
      max-width: 100%;
      max-height: 100%;
      height: 50px;
      margin-bottom:12px;
      font-size:16px;
      font-weight: 600/* Visina odgovara 2 reda teksta sa 1.8em line height */
  }
  
  .simplebar-track.simplebar-vertical {
     width: 7px;
  }
  
  .simplebar-scrollbar:before {
     background: currentColor;
  }

</style>

@section('content')
{{-- Prikaz pod-kategorija i pod-pod-kategorija --}}
<section class="position-relative">
  <div class="container py-5 py-lg-3 ">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb d-md-flex d-none">
        <li class="breadcrumb-item"><a href="{{ route('shop.homepage') }}">Početna</a></li>
        @if(!empty($categoryName))
            <li class="breadcrumb-item {{ empty($subCategoryName) && empty($subSubCategoryName) ? 'active' : 'text-dark' }}">
              @if(empty($subCategoryName) && empty($subSubCategoryName))
                {{ $categoryName }}
              @else
                <a href="{{ route('shop.shop', $categorySlug) }}" >{{ $categoryName }}</a>
              @endif
            </li>
        @endif
        @if(!empty($subCategoryName))
            <li class="breadcrumb-item {{ empty($subSubCategoryName) ? 'active' : 'text-dark' }}">
              @if(empty($subSubCategoryName))
                {{ $subCategoryName }}
              @else
                <a href="{{ route('shop.shop', $categorySlug . '/' . $subCategorySlug) }}">{{ $subCategoryName }}</a>
              @endif
            </li>
        @endif
        @if(!empty($subSubCategoryName))
            <li class="breadcrumb-item active" aria-current="page">{{ $subSubCategoryName }}</li>
        @endif
      </ol>
    </nav>

    <div class="row">

      @if($showSubCategories && !empty($categorySelected))
          @foreach($subCategories as $subCategory)
          <div class="col-lg-3 mb-3">
              <div class="card card-body border-0 p-3 bg-white position-relative">
                  <div class="d-flex flex-grow-1 justify-content-between align-items-center fw-semibold text-dark">
                      {{ $subCategory->name }}
                      <span class="badge bg-gray-200 text-dark flex-center rounded-circle width-2x height-2x ms-auto">
                        {{ $subCategory->products()->where('quantity', '>', 0)->count() }}
                    </span>
                  </div>
                  
                  <a href="{{ url('shop/' . $categorySlug . '/' . $subCategory->slug) }}" class="stretched-link"></a>
              </div>
          </div>
          @endforeach
      @endif

      @if($showSubSubCategories && !empty($subCategorySelected) && $subSubCategories->isNotEmpty())
          @foreach($subSubCategories as $subSubCategory)
          <div class="col-md-3 mb-3">
              <div class="card card-body  border-0  p-3 bg-white position-relative">
                  <div class="d-flex flex-grow-2 justify-content-between  align-items-center fw-semibold text-dark">
                      {{ $subSubCategory->name }}
                      <span class="badge bg-gray-200 text-dark flex-center rounded-circle width-2x height-2x ms-auto">
                          {{ $subSubCategory->products()->where('quantity', '>', 0)->count() }}
                      </span>
                  </div>
                  <a href="{{ url('shop/' . $categorySlug . '/' . $subCategorySlug . '/' . $subSubCategory->slug) }}" class="stretched-link"></a>
              </div>
          </div>
          @endforeach
      @endif



    </div>

    <hr />
  </div>
</section>


{{-- Prikaz filtera i proizvoda izabranih kategorija - pod-kategorija - pod-pod-kategorija --}}
<section class="position-relative mb-2">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 p-2 bg-white">
          <div class="row justify-content-between">
            <div class="col-12 col-md-2 mb-2 mb-md-0">
              <a href="#offcanvas_shop_filters" data-bs-toggle="offcanvas" aria-expanded="false" class="btn btn-secondary btn-sm w-100">
                <i class="bx bx-filter fs-5"></i> Filteri
              </a>
            </div>
            <div class="col-12 col-md-2">
              <select name="sortBy" class="form-select form-select-sm w-100" id="product-sortby">
                <option value="onStock" {{ $sortBy == 'onStock' ? 'selected' : '' }}>Na stanju</option>
                <option value="priceSortMin" {{ $sortBy == 'priceSortMin' ? 'selected' : '' }}>Cena - Rastuća</option>
                <option value="priceSortMax" {{ $sortBy == 'priceSortMax' ? 'selected' : '' }}>Cena - Opadajuća</option>
                <option value="sortNewest" {{ $sortBy == 'sortNewest' ? 'selected' : '' }}>Najnovije</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Offcanvas za filtere -->
<div class="offcanvas bg-white offcanvas-end" id="offcanvas_shop_filters">
  <div class="offcanvas-header justify-content-between border-bottom">
    <h5 class="mb-0">Filteri</h5>
    <button data-bs-dismiss="offcanvas" class="btn btn-secondary p-0 width-3x height-3x rounded-circle flex-center">
      <i class="bx bx-x"></i>
    </button>
  </div>
  <div class="offcanvas-body pt-5">
    <div class="mb-5">
      <h6 class="mb-4 text-body-secondary small text-uppercase">Brend:</h6>
      <ul class="list-unstyled pe-2 ps-1 py-1 mb-0" data-simplebar style="max-height:9.65rem;">
        @foreach($filteredBrands as $brand)
        <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
          <div class="form-check">
            <input class="form-check-input brand-checkbox" type="checkbox" id="brand_{{ $brand->id }}" value="{{ $brand->id }}" {{ in_array($brand->id, $brandsArray) ? 'checked' : '' }}>
            <label class="form-check-label widget-filter-item-text" for="brand_{{ $brand->id }}">{{ $brand->name }}</label>
          </div><span class="fs-xs text-body-secondary">{{ $brandCounts->get($brand->id, 0) }}</span>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="offcanvas-footer p-4 border-top d-flex align-items-center justify-content-between">
    <button id="applyFilters" data-bs-dismiss="offcanvas" type="button" class="btn btn-sm btn-primary">Potvrdi</button>
    <button id="clearFilters" type="button" class="btn btn-sm btn-danger">Očisti filtere</button>
  </div>
</div>

<section class="position-relative">
    <div class="container">
      
      <div class="row mb-5">
        @if ($products->isNotEmpty())
                @foreach ($products as $product)
                    @php
                        $productImage = $product->product_images->first();
                        $hasDiscount = !empty($product->discount_price);
                        $discountProcenat = $product->discount_percentage;
                    @endphp
                    <div class="col-md-6 col-xl-3 col-12 col-sm-6 mb-4">
                        <div class="card bg-light border-0 rounded-1 mt-2 mb-2  aos-init aos-animate h-100" data-aos="fade-up">
                          @if (!empty($productImage))
                              <a href="{{ route('shop.product', $product->slug) }}" class="d-block overflow-hidden rounded-top-4 image-container">
                                  <div class="image" style="background-image: url('{{ asset('uploads/product/large/'.$productImage->image) }}');"></div>
                              </a>
                          @elseif (!empty($product->brand->image))
                              <a href="{{ route('shop.product', $product->slug) }}" class="d-block overflow-hidden rounded-top-4 image-container">
                                  <div class="brand-image" style="background-image: url('{{ asset('uploads/brands/'.$product->brand->image) }}');"></div>
                              </a>
                          @endif
                            <div class="card-body p-3 px-lg-4">
                                
                                <a href="{{ route('shop.product',$product->slug) }}" class="text-dark d-block mb-7  outline-none">
                                    <!--Heading-->
                                    <div class="clamp-text">{{ $product->title }}</div>
                                </a>
                    
                                {{-- <div class="row mb-3 mb-lg-4">
                                    <div class="col-3" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                        data-bs-original-title="Bedrooms">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-bed fs-5 me-2"></i>
                                            <strong class="small">4</strong>
                                        </div>
                                    </div>
                                    <div class="col-3 border-start border-end" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                        data-bs-original-title="Bathrooms">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-bath fs-5 me-2"></i>
                                            <strong class="small">2</strong>
                                        </div>
                                    </div>
                                    <div class="col-6" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Area">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-area fs-5 me-2"></i>
                                            <strong class="small">6400 Sq Ft</strong>
                                        </div>
                                    </div>
                                </div> --}}
                                
                                <div class="row justify-content-between justify-content-lg-start align-items-center">
                        
                                    <div class="col-9">
                                        <!--Price-->
                                        {{-- <h4 class="mb-0">$983,000</h4> --}}
                
                                        @switch($hasDiscount)
                                            @case(true)
                                            <small class="fw-semibold text-danger">
                                                <s>{{ number_format($product->price,0,',','.')}} {{ $store->global_currency}}</s> <span> - {{floor($discountProcenat)}} % </span>
                                            </small>
                
                                            <h4 class="mb-0 text-dark">
                                                {{ number_format($product->discount_price,0,',','.')}} <span class="small fw-semibold text-reset fs-6"> {{ $store->global_currency}} </span>
                                            </h4>
                                            @break
                                        
                                            @default
                                            <h4 class="mb-0 text-dark">
                                                {{ number_format($product->price,0,',','.')}} <span class="small fw-semibold text-reset fs-6">{{ $store->global_currency}}</span>
                                            </h4>
                                        @endswitch
                
                                    </div>
                                    <div class="col-3">
                                        <!--Agent-->
                                        <div class="d-flex align-items-center justify-content-end flex-shrink-0">
                                            <a href="javascript:void(0)" onclick="addToCart({{ $product->id }});" class="btn btn-warning shadow-none width-5x height-5x rounded-circle d-flex align-items-center justify-content-center" id="add-to-cart-btn-{{ $product->id }}">
                                                <i class="bx bx-cart-add fs-3 text-secondary"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                           
                    </div>
                @endforeach
        @else
        <div class="col-md-12 d-flex align-items-center justify-content-center">
          Trenutno nema proizvoda iz tražene kategorije.
        </div>
        @endif
      </div>
    </div>
</section>
@endsection

@section('customJS')
<script src="{{ asset('assets/vendor/node_modules/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/vendor/node_modules/js/nouislider.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyFiltersButton = document.getElementById('applyFilters');

    applyFiltersButton.addEventListener('click', function() {
        // Dohvata sve selektovane brendove
        const selectedBrands = Array.from(document.querySelectorAll('.brand-checkbox:checked')).map(checkbox => checkbox.value);

        // Kreira URL sa parametrima za selektovane brendove
        const url = new URL(window.location.href);
        url.searchParams.set('brand', selectedBrands.join(','));

        // Redirektuje na novi URL sa filtrima
        window.location.href = url.toString();
    });

    const clearFiltersButton = document.getElementById('clearFilters');

    clearFiltersButton.addEventListener('click', function() {
       // Ukloni sve URL parametre
    const urlWithoutParams = window.location.protocol + "//" + window.location.host + window.location.pathname;
    
    // Osveži stranicu sa novim URL-om bez parametara
    window.location.href = urlWithoutParams;
    })
});
</script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
  const sortBySelect = document.getElementById('product-sortby');
  sortBySelect.addEventListener('change', function () {
    const selectedValue = sortBySelect.value;
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('sortBy', selectedValue);
    window.location.href = currentUrl.href;
  });
});
</script>
@endsection
