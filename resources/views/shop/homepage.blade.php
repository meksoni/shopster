@extends('shop.layouts.app')

@section('content')
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
    margin:0 auto;
}

.brand-image {
    width: 85%;
    height: 85%;
    background-size: contain; /* Osigurava da cela slika bude vidljiva */
    background-position: center;
    background-repeat: no-repeat; /* Sprečava ponavljanje slike */
    margin: 0 auto;
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

.swiper-wrapper {
    z-index: 0;
}
</style>

{{-- Product Istaknuto --}}
<section class="overflow-hidden">
    <div class="container py-9 py-lg-9">
        <div class="row mb-2 align-items-center">
            <div class="col-md-7 text-center text-md-start mb-2">
                <h2 class="mb-0 fw-semibold">
                    Istaknuti proizvodi
                </h2>
            </div>
        </div>

        <div class="swiper-featured swiper-container position-relative overflow-hidden">
            <div class="swiper-wrapper">
                @if($products->isNotEmpty())
                    @foreach ($products as $product)
                    @if ($product->is_featured == 'Yes')
                        @php
                        $productImage = $product->product_images->first();
                        $hasDiscount = !empty($product->discount_price);
                        $discountProcenat = $product->discount_percentage;
                        @endphp
                        <!--Slide product-->
                        <div class="swiper-slide">
                            <div class="card bg-light border-0 z-index-0 rounded-1 mt-2 mb-2  aos-init aos-animate h-100" data-aos="fade-up">
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
                            <!--/Card product end-->
                        </div>
                    @endif
                    @endforeach
                @endif
            </div>
            <!--Pagination-->
            <div class="swiperFeatured-pagination swiper-pagination position-static pt-5"></div>
        </div>

    </div>    
</section>

{{-- Spisak kategorija --}}
@if(!empty($categories))
<section class="position-relative mb-5">
    <div class="container position-relative">
        <div data-aos="fade-up" class="bg-white rounded-3 text-white px-4 py-7 py-lg-9 px-lg-9 shadow-lg position-relative z-0">
        <!--Swiper thumbnails-->
        <div class="swiper-container position-relative overflow-hidden swiper-partners">
            <div class="swiper-wrapper">

                @foreach($categories as $category)

                <div class="swiper-slide">
                    <a href="{{ route('shop.shop', $category->slug) }}">
                    <div class="d-flex p-2 rounded-2 flex-column align-items-center justify-content-end gap-5 ">
                        <img src="{{ asset('uploads/categories/' . $category->image)}}" alt="{{ $category->name}}" style="height:80px;" class="img-fluid">
                        <h5 class="text-dark">{{ $category->name }}</h5>
                    </div>
                    </a>
                </div>

                @endforeach

            </div>

            <!--Pagination-->
           
        </div>
        <!-- / Swiper thumbnails-->
        </div>
    </div>
</section>
@endif

{{-- Brendovi products istaknuto --}}
@if(!empty($selectedBrands))
<section class="overflow-hidden">
    <div class="container py-9 py-lg-3">
        @php
            $availableProducts = $selectedBrands->filter(function($product) {
                return $product->quantity > 0;
            });

            $brandLink = $availableProducts->first()->brand->id;
        @endphp

        <div class="row position-relative mb-4 align-items-center">
            <div class="col-12 col-lg-7 me-auto mb-4 mb-lg-0" data-aos="fade-up">
                <!--Title-->
                <div class="position-relative">
                    <!--Subtitle-->
                    <div class="d-flex align-items-center mb-3" data-aos="fade-up">
                        <div class="border-top border-primary width-3x border-2"></div>
                        <h5 class="mb-0 ms-3 text-body-secondary fw-semibold">
                        @if($availableProducts->isNotEmpty() && !empty($availableProducts->first()->brand))
                            {{ $availableProducts->first()->brand->name ?? 'Brend' }}
                        @endif
                        </h5>
                    </div>
                    <h2 class="mb-0">
                        Istaknuta ponuda
                    </h2>
                </div>
            </div>


            <div class="col-lg-4 text-lg-end" data-aos="fade-down" data-aos-delay="100">
                <!--Action button-->
                <a href="{{ route('shop.shop', ['brand' => $brandLink])}}"
                    class="btn flex-center hover-lift btn-outline-dark p-2 btn-rise">
                    <div class="btn-rise-bg bg-dark"></div>
                    <div class="btn-rise-text">
                        <span class="small">Proizvodi {{ $availableProducts->first()->brand->name }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="swiper-featured swiper-container position-relative overflow-visible">
            <div class="swiper-wrapper">
               
                @foreach ($selectedBrands as $product)
                    @php
                    $productImage = $product->product_images->first();
                    $hasDiscount = !empty($product->discount_price);
                    $discountProcenat = $product->discount_percentage;
                    @endphp
                    <!--Slide product-->
                    <div class="swiper-slide">
                        <div class="card bg-light border-0 rounded-1 mt-2 mb-2 aos-init aos-animate h-100" data-aos="fade-up">
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
                                <a href="{{ route('shop.product',$product->slug) }}" class="text-dark d-block mb-7 outline-none">
                                    <div class="clamp-text">{{ $product->title }}</div>
                                </a>
                    
                                <div class="row justify-content-between justify-content-lg-start align-items-center">
                                    <div class="col-9">
                                        @switch($hasDiscount)
                                            @case(true)
                                            <small class="fw-semibold text-danger">
                                                <s>{{ number_format($product->price, 0, ',', '.') }} {{ $store->global_currency }}</s> <span> - {{ floor($discountProcenat) }} % </span>
                                            </small>
                    
                                            <h4 class="mb-0 text-dark">
                                                {{ number_format($product->discount_price, 0, ',', '.') }} <span class="small fw-semibold text-reset fs-6"> {{ $store->global_currency }} </span>
                                            </h4>
                                            @break
                                        
                                            @default
                                            <h4 class="mb-0 text-dark">
                                                {{ number_format($product->price, 0, ',', '.') }} <span class="small fw-semibold text-reset fs-6">{{ $store->global_currency }}</span>
                                            </h4>
                                        @endswitch
                                    </div>
                                    <div class="col-3">
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
            </div>
            <!--Pagination-->
            <div class="swiperFeatured-pagination swiper-pagination position-static pt-5"></div>
        </div>
    </div>
</section>
@endif


{{-- Kategorije products istaknuto --}}
@if(!empty($selectedCategories))
<section class="overflow-hidden">
    <div class="container py-9 py-lg-9">
        <div class="row position-relative mb-4 align-items-center">
            <div class="col-12 col-lg-7 me-auto mb-4 mb-lg-0" data-aos="fade-up">
                <!--Title-->
                <div class="position-relative">
                    <!--Subtitle-->
                    <div class="d-flex align-items-center mb-3" data-aos="fade-up">
                        <div class="border-top border-warning width-3x border-2"></div>
                        <h5 class="mb-0 ms-3 text-body-secondary fw-semibold">
                            {{ $selectedCategories->first()->category->name ?? 'Kategorija' }}
                        </h5>
                    </div>
                    <h2 class="mb-0">
                        Istaknuta ponuda
                    </h2>
                </div>
            </div>

            @php 
                $categoryLink = $selectedCategories->first()->category->slug;
            @endphp

            <div class="col-lg-4 text-lg-end" data-aos="fade-down" data-aos-delay="100">
                <!--Action button-->
                <a href="{{ route('shop.shop', $categoryLink)}}"
                    class="btn flex-center hover-lift btn-outline-dark p-2 btn-rise">
                    <div class="btn-rise-bg bg-dark"></div>
                    <div class="btn-rise-text">
                        <span class="small">Proizvodi {{ $selectedCategories->first()->category->name }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="swiper-featured swiper-container position-relative overflow-visible">
            <div class="swiper-wrapper">
               
                    @foreach ($selectedCategories as $product)
                        @php
                        $productImage = $product->product_images->first();
                        $hasDiscount = !empty($product->discount_price);
                        $discountProcenat = $product->discount_percentage;
                        @endphp
                        <!--Slide product-->
                        <div class="swiper-slide">
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
                            <!--/Card product end-->
                        </div>
                    @endforeach
                </div>
                <!--Pagination-->
                <div class="swiperFeatured-pagination swiper-pagination position-static pt-5"></div>
            </div>
            
        </div>    
</section>
@endif


{{-- Podkategorije products istaknuto --}}
@if(!empty($selectedSubcategories))
<section class="overflow-hidden">
    <div class="container py-9 py-lg-9">
        <div class="row position-relative mb-4 align-items-center">
            <div class="col-12 col-lg-7 me-auto mb-4 mb-lg-0" data-aos="fade-up">
                <!--Title-->
                <div class="position-relative">
                    <!--Subtitle-->
                    <div class="d-flex align-items-center mb-3" data-aos="fade-up">
                        <div class="border-top border-danger width-3x border-2"></div>
                        <h5 class="mb-0 ms-3 text-body-secondary fw-semibold">
                            {{ $selectedSubcategories->first()->sub_category->name ?? 'Pod-Kategorija' }}
                        </h5>
                    </div>
                    <h2 class="mb-0">
                        Istaknuta ponuda
                    </h2>
                </div>
            </div>

            @php 
                $categorySlug = $selectedSubcategories->first()->category->slug;
                $subCategoryLink = $selectedSubcategories->first()->sub_category->slug;
            @endphp

            <div class="col-lg-4 text-lg-end" data-aos="fade-down" data-aos-delay="100">
                <!--Action button-->
                <a href="{{ route('shop.shop', [$categorySlug,  $subCategoryLink]) }}"
                    class="btn flex-center hover-lift btn-outline-dark p-2 btn-rise">
                    <div class="btn-rise-bg bg-dark"></div>
                    <div class="btn-rise-text">
                        <span class="small">Proizvodi {{ $selectedSubcategories->first()->sub_category->name }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="swiper-featured swiper-container position-relative overflow-visible">
            <div class="swiper-wrapper">
               
                    @foreach ($selectedSubcategories as $product)
                        @php
                        $productImage = $product->product_images->first();
                        $hasDiscount = !empty($product->discount_price);
                        $discountProcenat = $product->discount_percentage;
                        @endphp
                        <!--Slide product-->
                        <div class="swiper-slide">
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
                            <!--/Card product end-->
                        </div>
                    @endforeach
                </div>
                <!--Pagination-->
                <div class="swiperFeatured-pagination swiper-pagination position-static pt-5"></div>
            </div>
            
        </div>    
</section>
@endif


{{-- Podpodkategorije products istaknuto --}}
@if(!empty($selectedSubSubCategories))
<section class="overflow-hidden">
    <div class="container py-9 py-lg-9">

        <div class="row position-relative mb-4 align-items-center">
            <div class="col-12 col-lg-7 me-auto mb-4 mb-lg-0" data-aos="fade-up">
                <!--Title-->
                <div class="position-relative">
                    <!--Subtitle-->
                    <div class="d-flex align-items-center mb-3" data-aos="fade-up">
                        <div class="border-top border-success width-3x border-2"></div>
                        <h5 class="mb-0 ms-3 text-body-secondary fw-semibold">
                            {{ $selectedSubSubCategories->first()->sub_sub_category->name ?? 'Pod-pod-kategorija' }}
                        </h5>
                    </div>
                    <h2 class="mb-0">
                        Istaknuta ponuda
                    </h2>
                </div>
            </div>

            @php 
                $categorySlug = $selectedSubSubCategories->first()->category->slug;
                $subCategoryLink = $selectedSubSubCategories->first()->sub_category->slug;
                $subSubCategoryLink = $selectedSubSubCategories->first()->sub_sub_category->slug
            @endphp

            <div class="col-lg-4 text-lg-end" data-aos="fade-down" data-aos-delay="100">
                <!--Action button-->
                <a href="{{ route('shop.shop', [$categorySlug,  $subCategoryLink, $subSubCategoryLink]) }}"
                    class="btn flex-center hover-lift btn-outline-dark p-2 btn-rise">
                    <div class="btn-rise-bg bg-dark"></div>
                    <div class="btn-rise-text">
                        <span class="small">Proizvodi {{ $selectedSubSubCategories->first()->sub_sub_category->name }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="swiper-featured swiper-container position-relative overflow-visible">
            <div class="swiper-wrapper">
               
                    @foreach ($selectedSubSubCategories as $product)
                        @php
                        $productImage = $product->product_images->first();
                        $hasDiscount = !empty($product->discount_price);
                        $discountProcenat = $product->discount_percentage;
                        @endphp
                        <!--Slide product-->
                        <div class="swiper-slide">
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
                            <!--/Card product end-->
                        </div>
                    @endforeach
                </div>
                <!--Pagination-->
                <div class="swiperFeatured-pagination swiper-pagination position-static pt-5"></div>
            </div>
            
        </div>    
</section>
@endif

<hr />

{{-- INFO --}}
<section class="position-relative text-dark overflow-hidden">
    <div class="container py-7 position-relative">
      <div class="row align-items-center">
        <div class="col-md-4 border-end-md text-center mb-7 mb-md-0">
          <div class="mb-3">
            <i class="bx bx-store fs-1"></i>
          </div>
          <h6 class="mb-0">Do 30 dana reklamacije</h6>
        </div>
        
        <div class="col-md-4 border-end-md text-center mb-7 mb-md-0">
          <div class="mb-3">
            <i class="bx bx-purchase-tag fs-1"></i>
          </div>
          <h6 class="mb-0">100% Zadovoljnih kupaca</h6>
        </div>
        <div class="col-md-4 text-center">
          <div class="mb-3">
            <i class="bx bx-package fs-1"></i>
          </div>
          <h6 class="mb-0">Garantovani kvalitet</h6>
        </div>
      </div>
    </div>
</section>

@endsection

@section('customJS')
<script src="{{asset('assets/vendor/node_modules/js/swiper-bundle.min.js')}}"></script>
<script>
    //Swiper Featured Products
    var swiperClassic = new Swiper(".swiper-featured", {
                slidesPerView: 1,
                spaceBetween: 16,
                breakpoints: {
                    480: {
                        slidesPerView: 1,
                        spaceBetween: 16,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 16,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 16,
                    },
                },
                pagination: {
                    el: ".swiperFeatured-pagination",
                    clickable: true
                }
            });

            var swiperPartners5 = new Swiper(".swiper-partners", {
                slidesPerView: 1,
                loop: true,
                spaceBetween: 16,
                autoplay: true,
                breakpoints: {
                    768: {
                        slidesPerView: 4
                    },
                    1024: {
                        slidesPerView: 4
                    }
                },
                pagination: {
                    el: ".swiper-partners-pagination",
                    clickable: true
                },
                navigation: {
                    nextEl: ".swiper-partners-button-next",
                    prevEl: ".swiper-partners-button-prev"
                }
            });

            

        
</script>
@endsection


