
 
 <nav class="navbar navbar-info navbar-expand-md shadow-xl header-center-logo bg-white position-sticky z-1 top-0">
     <div class="container position-relative">
         <a class="navbar-brand text-dark fw-semibold" href="{{ route('shop.homepage') }}">
             {{-- <img src="{{ asset('storage/logo/alfasoft-black.png') }}" class="img-fluid w-50" alt=""> --}}
             <span class="text-primary">{{$store->cp_name}}</span>
         </a>
        <div class="d-flex align-items-center navbar-no-collapse-items navbar-nav flex-row order-md-last">
             <button class="navbar-toggler text-dark order-last" type="button" data-bs-toggle="offcanvas"
                 data-bs-target="#offcanvasCart" aria-controls="offcanvasCart" aria-expanded="false"
                 aria-label="Toggle navigation">
                
                 <span class="navbar-toggler-icon">
                     <i></i>
                 </span>
             </button>
             {{-- <div class="nav-item me-3 ms-0">
                 <a href="" class="nav-link lh-1 position-relative">
                     <i class="bx bx-heart fs-4 text-dark"></i>
                 </a>
             </div> --}}
             <div class="nav-item me-3 ms-0">
                <a href="{{ route('shop.cart') }}" class="nav-link lh-1 position-relative">
                    <i class="bx bx-cart fs-4 text-dark"></i>
                    <span id="cart-count"
                    class="badge d-none p-0 position-absolute end-0 top-0 me-n2 mt-n1 lh-1 fw-semibold width-1x height-1x shadow-sm bg-warning text-dark rounded-circle flex-center">
                    {{ Cart::count() }}
                    </span>
                </a>
             </div>
 
             {{-- <a href="{{ route('front.cart') }}" class="nav-link lh-1 position-relative">
                 <i class="bx bx-cart fs-3"></i>
                 Korpa
                 <span id="cart-count" class="badge d-none p-0 position-absolute start-0 top-0 translate-middle ms-n0 mt-n0 lh-1 width-1x height-1x bg-warning text-dark shadow-sm rounded-circle flex-center">
                     {{ Cart::count() }}
                 </span>  
             </a> --}}
 
             
             <div class="nav-item me-3 ms-0">
                <a href="#" data-bs-target="#modal-search-bar-2" data-bs-toggle="modal" class="nav-link lh-1">
                    <i class="bx bx-search text-dark fs-4"></i>
                </a>
            </div>
        </div>


         <div class="collapse navbar-collapse order-md-start" id="mainNavbarTheme">
             <ul class="navbar-nav">
                 <div class="d-flex align-items-center navbar-no-collapse-items order-lg-start">
                    
                    <a class="btn btn-sm btn-primary text-start ps-8 pe-8 text-start fs-6 rounded-0" href="#offcanvasCart" data-bs-toggle="offcanvas">
                        <div class="d-flex align-items-center gap-1">
                            <i class="bx bx-menu-alt-right"></i>
                            <span>PROIZVODI</span>
                        </div>
                    </a>
                    
                 </div>
 
             </ul>
 
         </div>
     </div>
 </nav>
 

 <div id="modal-search-bar-2" class="modal fade" tabindex="-1" aria-labelledby="modal-search-bar-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top modal-md">
        <div class="modal-content bg-light position-relative border-0">
            <div class="position-relative px-4">
                <div class="position-absolute end-0 width-6x top-0 d-flex me-0 align-items-center h-100 justify-content-center">
                    
                    <button class="border-0 bg-white w-auto small">
                        <svg class="text-dark" data-bs-dismiss="modal" aria-label="Close" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                        </svg>
                    </button>
                </div>
                <form class="mb-0">
                    <div class="d-flex align-items-center">
                        <div class="d-flex flex-grow-1 align-items-center">
                            <i class="bx bx-search fs-4"></i>
                            <input type="text" id="search-input" placeholder="Unesite pojam za pretragu...." class="form-control shadow-none border-0 flex-grow-1 form-control-lg">
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-4 border-top">
                <div class="d-flex align-items-center mb-3">
                    {{-- <i class="bx bx-trending-up fs-4"></i> --}}
                    <h6 class="mb-0 ms-2">Rezultat pretrage:</h6>
                </div>
                
                <div id="search-results" class="d-flex flex-wrap align-items-center">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas bg-light offcanvas-start " tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCart">
    <div class="border-bottom border-gray offcanvas-header align-items-center justify-content-between">
        <h5 class="mb-0 text-dark fw-semibold"><span class="text-primary">{{$store->cp_name}}</span></h5>
        <button type="button"
        class="btn btn-secondary p-0 m-0 width-3x height-3x flex-center ms-auto"
        data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="bx bx-x fs-4"></i>
    </button>
    </div>
    <div class="">
        <ul class="nav flex-column d-flex no-animation mb-0">
            @if ($categories->isNotEmpty())
                @foreach ($categories as $category)
                <li class="nav-item custom-nav-item px-3">
                    <a class="nav-link d-flex align-items-center text-transform-none" href="{{ route('shop.shop', $category->slug) }}">
                        @if ($category->image != "")
                            <span class="me-2">
                                <img src="{{ asset('uploads/categories/' . $category->image) }}" style="height:25px; padding:3px;" alt="{{ $category->name }}">
                            </span>
                        @endif
                        <!--Sidebar nav text-->
                        <span class="sidebar-text text-dark">{{ $category->name }}</span>
                        <!-- Prikaz broja dostupnih proizvoda -->
                        <span class="badge bg-gray-200 text-dark rounded-circle width-2x height-2x flex-center ms-auto">{{ $category->available_products_count }}</span>
                    </a>
                </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<script>
   document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value;

        if (query.length > 2) {
            fetch(`{{ route('search') }}?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('search-results');
                    resultsContainer.innerHTML = '';

                    data.forEach(product => {
                        const link = document.createElement('a');
                        link.href = `{{ url('product') }}/${product.slug}`;
                        link.classList.add('list-group-item', 'list-group-item-action' , 'text-truncate' , 'fw-semibold', 'mb-2', 'py-2');
                        link.style.borderBottom = '1px solid #ccc'; // Dodaj border

                        // Kreiramo element za sliku
                        const productImage = product.product_images.length > 0 ? product.product_images[0].image : null;
                        const imageElement = document.createElement('img');
                        imageElement.alt = product.title;
                        imageElement.classList.add('img-fluid', 'rounded-circle', 'me-2'); // Dodaj klase za stilizovanje
                        imageElement.style.width = '30px'; // Postavi širinu slike
                        imageElement.style.height = '30px'; // Postavi visinu slike
                        imageElement.style.zIndex = 10; // Povećajte z-index

                        if (productImage) {
                            imageElement.src = `{{ asset('uploads/product/small/') }}/${productImage}`;
                        } else {
                            imageElement.src = `{{ asset('storage/products/box.png') }}`; // Postavi podrazumevanu sliku ako nema
                        }

                        // Dodaj sliku u link
                        link.prepend(imageElement); // Dodaj sliku pre teksta
                        link.append(` ${product.title}`); // Dodaj naziv proizvoda kao tekst posle slike

                        resultsContainer.appendChild(link);
                    });
                });
        }
    });
});
</script>