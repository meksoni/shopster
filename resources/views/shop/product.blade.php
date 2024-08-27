@extends('shop.layouts.app')


@section('content')
@php
// $productImage = $product->product_images->first();
@endphp
<style>
.image-container {
    width: 100%; /* Možeš postaviti fiksnu širinu ako je potrebno, npr. width: 200px; */
    height: 500px; /* Postavi visinu koja ti odgovara */
    background-color: white; /* Bela pozadina box-a */
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-wrapper {
    z-index: 0;
}

.img-magnifier-glass {
    position: absolute;
    border: 3px solid #000;
    border-radius: 50%;
    cursor: none;
    /*Set the size of the magnifier glass:*/
    width: 100px;
    height: 100px;
}

/* Stil za slike unutar box-a */
.image {
    max-width: 70%; /* Ograničava maksimalnu širinu slike na 80% */
    max-height: 70%; /* Ograničava maksimalnu visinu slike na 80% */
    width: auto;
    height: auto;
    object-fit: contain; /* Osigurava da slika zadrži svoj proporcijalni odnos i bude centrirana */
    margin: auto; /* Centriranje slike unutar kontejnera */
    display: block; /* Osigurava da se margin auto primeni pravilno */
}
</style>

<section class="position-relative bg-white">
  <div class="container py-5 py-lg-2">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb d-md-flex d-none">
          @foreach ($breadcrumbs as $breadcrumb)
              @if ($loop->last)
                  <li class="breadcrumb-item active fs-6" aria-current="page">{{ $breadcrumb['name'] }}</li>
              @else
                  <li class="breadcrumb-item">
                      <a class="fs-6" href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                  </li>
              @endif
          @endforeach
      </ol>

      <ol class="breadcrumb d-md-none d-block">
          <li class="breadcrumb-item">               
              <a href="{{ url()->previous() }}" class=" fw-semibold fs-6">
                  <i class="bx bx-left-arrow-alt ms-1 fs-5"></i>
                  Vratite se
              </a>              
          </li>
      </ol>
  </nav>

    <hr />
  </div>
</section>

{{-- Detalji proizvoda --}}
<section class="position-relative bg-white text-dark">
    <div class="container pt-7 pt-lg-1 pb-9 pb-lg-2 position-relative">
      <nav class="d-md-flex" aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
         
        </ol>
      </nav>
      <div class="row justify-content-between">
        <div class="col-xl-8 col-lg-7 col-md-8 mx-auto mx-lg-0 mb-5 mb-lg-0">
          <div class="row g-1 justify-content-between">
            <div class="col-2">
                <!-- Thumbnails for main slider (just above) -->
                <div class="swiper-container swiper-thumbnails overflow-hidden">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper d-flex flex-column">
                        <!-- Slides -->
                        @if ($product->product_images && $product->product_images->isNotEmpty())
                            @foreach ($product->product_images as $key => $productImage)
                                <div class="swiper-slide w-100 mb-2">
                                    <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="" class="w-100 rounded-0 h-auto">
                                </div>
                            @endforeach
                        @elseif (!empty($product->brand->image))
                            <div class="swiper-slide w-100 mb-2">
                                <img src="{{ asset('uploads/brands/'.$product->brand->image) }}" alt="{{ $product->brand->name }}" class="w-100 rounded-0 h-auto">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-10">
                <!-- Thumbnails main slider -->
                <div class="swiper-container overflow-hidden position-relative swiper-thumbnails-main">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper w-100">
                        <!-- Slides -->
                        @if ($product->product_images && $product->product_images->isNotEmpty())
                            @foreach ($product->product_images as $key => $productImage)
                                <div class="swiper-slide image-container">
                                    <img src="{{ asset('uploads/product/large/'.$productImage->image) }}"
                                          data-magnify-src="{{ asset('uploads/product/large/'.$productImage->image) }}"
                                          alt="" class="image zoom">
                                </div>
                            @endforeach
                        @elseif (!empty($product->brand->image))
                            <div class="swiper-slide image-container">
                                <img src="{{ asset('uploads/brands/'.$product->brand->image) }}"
                                      data-magnify-src="{{ asset('uploads/brands/'.$product->brand->image) }}"
                                      alt="{{ $product->brand->name }}" class="image zoom">
                            </div>
                        @endif
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        
        </div>
        
        
        <!--/.col-->
        <div class="col-md-8 mx-auto col-lg-4 ms-xl-auto">
          <!--Breadcrumbs-->
          <!-- Product Description -->
          <div class="pb-md-0 pb-3">
            <div class="mb-3">
              <h3 class="mb-2">{{ $product->title }}</h3>
              <div>
                <span class="text-warning d-block mb-4">
                  <i class="bx bx-star fill"></i>
                  <i class="bx bx-star"></i>
                  <i class="bx bx-star"></i>
                  <i class="bx bx-star"></i>
                  <i class="bx bx-star"></i>
                </span>
              </div>

              @if (!empty($product->brand->image))
              <div class="mb-4">
                <img src="{{ asset('uploads/brands/'.$product->brand->image) }}" alt="{{ $product->brand->name }}" class="img-fluid overflow-hidden w-25">
              </div>
              @endif

              <div class="info mb-4">
                <p class="text-secondary">Šifra proizvoda: <strong class="text-dark">{{$product->sku}}</strong></p>
                <p class="text-secondary d-flex align-items-center gap-1">Dostupnost: <span class="text-dark fw-semibold">
                    @if ($product->quantity == 0)

                        <strong class="text-danger d-flex align-items-center">
                            <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                              viewBox="0 0 8 8">
                              <path
                                d="M2 0v1h1v.03c-1.7.24-3 1.71-3 3.47 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5c0-.45-.1-.87-.25-1.25l-.91.38c.11.29.16.57.16.88 0 1.39-1.11 2.5-2.5 2.5s-2.5-1.11-2.5-2.5 1.11-2.5 2.5-2.5c.3 0 .59.05.88.16l.34-.94c-.23-.08-.47-.12-.72-.16v-.06h1v-1h-3zm5 1.16s-3.65 2.81-3.84 3c-.19.2-.19.49 0 .69.19.2.49.2.69 0 .2-.2 3.16-3.69 3.16-3.69z">
                              </path>
                            </svg>
                            Proizvod nije na stanju
                            </strong>

                    @elseif ($product->quantity < 5)
                        <strong class="text-warning d-flex align-items-center">
                        <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                          viewBox="0 0 8 8">
                          <path
                            d="M2 0v1h1v.03c-1.7.24-3 1.71-3 3.47 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5c0-.45-.1-.87-.25-1.25l-.91.38c.11.29.16.57.16.88 0 1.39-1.11 2.5-2.5 2.5s-2.5-1.11-2.5-2.5 1.11-2.5 2.5-2.5c.3 0 .59.05.88.16l.34-.94c-.23-.08-.47-.12-.72-.16v-.06h1v-1h-3zm5 1.16s-3.65 2.81-3.84 3c-.19.2-.19.49 0 .69.19.2.49.2.69 0 .2-.2 3.16-3.69 3.16-3.69z">
                          </path>
                        </svg>
                        Proverite stanje ovog proizvoda
                        </strong>
                    @elseif ($product->quantity >= 5)
                    <strong class="text-success d-flex align-items-center">
                        <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                          viewBox="0 0 8 8">
                          <path
                            d="M2 0v1h1v.03c-1.7.24-3 1.71-3 3.47 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5c0-.45-.1-.87-.25-1.25l-.91.38c.11.29.16.57.16.88 0 1.39-1.11 2.5-2.5 2.5s-2.5-1.11-2.5-2.5 1.11-2.5 2.5-2.5c.3 0 .59.05.88.16l.34-.94c-.23-.08-.47-.12-.72-.16v-.06h1v-1h-3zm5 1.16s-3.65 2.81-3.84 3c-.19.2-.19.49 0 .69.19.2.49.2.69 0 .2-.2 3.16-3.69 3.16-3.69z">
                          </path>
                        </svg>
                        Na stanju
                      </strong>
                    @endif
                </span>
                </p>
              </div>

              <hr class="mb-3"/>

              @php
                $hasDiscount = !empty($product->discount_price);
                $discountProcenat = $product->discount_percentage;
              @endphp
              
               
              <div class="d-flex mb-0 justify-content-between align-items-center">
                <div>
                  @switch($hasDiscount)
                  @case(true)
                  <small class="fw-semibold text-danger">
                      <s>{{ number_format($product->price,0,',','.')}} {{ $store->global_currency}}</s> <span> - {{floor($discountProcenat)}} % </span>
                  </small>

                  <h2 class="mb-0 text-dark">
                      {{ number_format($product->discount_price,0,',','.')}} <span class="small fw-semibold text-reset fs-6"> {{ $store->global_currency}} </span>
                  </h2>
                  @break
              
                  @default
                  <h2 class="mb-0 text-dark">
                      {{ number_format($product->price,0,',','.')}} <span class="small fw-semibold text-reset fs-6">{{ $store->global_currency}}</span>
                  </h2>
                  @endswitch
  
                </div>
                <div>
                  <a href="#" class="link-info fw-semibold small"><i class="bx bx-heart align-middle me-2"></i>Dodaj u listu želja</a>
                </div>

              </div>
            </div>
            
          </div>

         
          
          <hr class="mb-3"/>
          <div class=" d-flex align-items-center">
            <i class="bx bxs-truck fs-5 me-1"></i>
            <h6 class="mb-0 ms-3">ROK ISPORUKE<br> 2-5 RADNIH DANA</h6>
          </div>
          <hr class="mb-3"/>

          <div class="d-grid">
            @if ($product->quantity == 0)
            <a   class="btn btn-warning hover-lift disabled">
              <i class="bx bx-shopping-bag fs-5 me-2"></i>
              Dodaj u korpu
            </a>
            @else
            <a  onclick="addToCart({{ $product->id }});" class="btn btn-warning hover-lift" id="add-to-cart-btn-{{ $product->id }}">
              <i class="bx bx-cart fs-5 me-2"></i>
              Dodaj u korpu
            </a>
            @endif 
          </div>
          <!--/.cart-action-->

        </div>
        <!--/.col-->
      </div>
    </div>
</section>
{{-- Specifikacije proizvoda --}}
<section class="bg-white text-dark">
    <div class="container py-9 py-lg-11">
      <div class="row justify-content-center">
        <div class="col-lg-10 mb-5">
          <nav class="nav nav-tabs">
            <a  class="nav-link text-dark fs-3 active" data-bs-toggle="tab" aria-haspopup="false"
              aria-expanded="true">
              Specifikacije
            </a>
            {{-- <a href="#information" class="nav-link" data-bs-toggle="tab" aria-haspopup="false"
              aria-expanded="false">
              Information
            </a>
            <a href="#reviews" class="nav-link" data-bs-toggle="tab" aria-haspopup="false" aria-expanded="false">
              Reviews
            </a>
            <a href="#product-qa" class="nav-link" data-bs-toggle="tab" aria-haspopup="false" aria-expanded="false">
              Q&amp;A
            </a> --}}
          </nav>
        </div>
        <!--/.col-->
        @if ($product->specifications->isNotEmpty())
        <div class="col-lg-10 col-md-8">
          <div class="tab-content">
            <div class="tab-pane fade active show" id="specifikacije">
                    <div class="table-responsive">
                        <table class="table table-white table-striped table-borderles ">

                            <tbody>
                                @foreach($product->specifications as $specification)
                                <tr>
                                    <td class="text-dark">{{ $specification->name }}</td>
                                    <td class="text-dark">{{ $specification->value }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
        <!--Tab-pane-->
        @else
        <p>Nema dostupnih specifikacija za ovaj proizvod.</p>
        @endif
    </div>
      </div>
    </div>
</section>

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
     //Swiper thumbnail demo
     var swiperThumbnails = new Swiper(".swiper-thumbnails", {
        spaceBetween: 8,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
      });
      var swiperThumbnailsMain = new Swiper(".swiper-thumbnails-main", {
        spaceBetween: 0,
        pagination: {
          el: '.swiper-pagination',
        },
        loop:true,
        thumbs: {
          swiper: swiperThumbnails
        }
        
      });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {

    $(document).ready(function () { 
              $(".zoom").magnify(); 
          }); 
  })
</script>
@endsection