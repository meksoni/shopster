<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopster | neodigital.pro | Web Solutions</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo/alfasoft-black.png') }}">
    @vite(['resources/shop/scss/shop.scss', 'resources/shop/js/shop.js'])
    <link rel="stylesheet" href="{{ asset('storage/boxicons/css/boxicons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/node_modules/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/node_modules/css/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/node_modules/css/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/vendor/node_modules/css/nouislider.min.css') }}">


    @yield('customCSS')
    <style>
        @media (max-width: 767px) {
            #toastContainer {
                transform: translate(-50%, -50%);
            }
        }

        .loader {
          width: 32px;
          aspect-ratio: 1;
          border-radius: 50%;
          border: 3px solid #f2f2f2;
          border-right-color: orange;
          animation: l2 1s infinite linear;
        }
        @keyframes l2 {to{transform: rotate(1turn)}}
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body style="background:#f2f2f2;">

    
    
    @if(!isset($hideNavbar) || !$hideNavbar)
        @include('shop.layouts.navbar')
    @endif

    <div id="toastContainer" class="position-fixed p-3" aria-live="assertive" aria-atomic="true" style="z-index: 1050;">
        <!-- Toasts će biti dinamički dodavani ovde -->
    </div>

    
    <main>
        @if (Session::has('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            
            

        @yield('content')
    </main>

    <!--begin:footer-->
    <footer class="position-relative bg-light overflow-hidden" data-bs-theme="dark">
        <div class="container pt-9 pt-lg-11 pb-6 position-relative">
            <div class="row">
                <div class="col-6 col-lg-3 col-xl-2 order-lg-2 ms-lg-auto mb-6">
                    <h6 class="mb-4 text-dark-50">Top Kategorije</h6>
                    <ul class="nav flex-column mb-0">
                        @foreach ($footerCategories as $category)
                            <li class="nav-item"><a class="nav-link" href="{{route('shop.shop', $category->slug)}}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="col-6 col-lg-3 col-xl-2 order-lg-3 ms-lg-auto mb-6">
                    <h6 class="mb-4 text-dark-50">Top Brendovi</h6>
                    <ul class="nav flex-column mb-0">
                        @foreach ($footerBrands as $brand)
                            <li class="nav-item"><a class="nav-link" href="{{ route('shop.shop', ['brand' => $brand->id]) }}">{{ $brand->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                {{-- <div class="col-lg-3 col-md-6 order-lg-1 mb-6">
                    <div class="d-flex align-items-md-stretch flex-column h-100">
                        <div class="flex-grow-1 d-flex flex-column">
                            <span class="d-block text-body-tertiary mb-3">
                                745K Followers
                            </span>
                            <div class="mb-4">
                                <a href="#!" class="btn btn-outline-dark btn-rise">
                                    <div class="btn-rise-bg bg-white"></div>
                                    <div class="btn-rise-text">
                                        <i class="bx bxl-instagram me-1 align-middle fs-5"></i> Follow us on IG
                                    </div>
                                </a>
                            </div>
                            <!--:Dark Mode:-->
                            <div class="d-inline-flex width-13x align-items-center dropup mt-6">
                                <button class="btn border text-body py-2 px-2 d-flex align-items-center"
                                    id="assan-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                                    data-bs-display="static">
                                    <span class="theme-icon-active d-flex align-items-center">
                                        <i class="bx align-middle"></i>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Copyright -->
                        <p class="small text-body-secondary mb-0">© Assan. by Creative DM</p>
                        <!-- End Copyright -->
                    </div>
                </div> --}}
            </div>
            <hr class="bg-transparent border-top border-white opacity-25 mb-6 mt-0">
            <div class="row align-items-md-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <!--:payment options-->
                    <div class="d-flex justify-content-start">

                        <div class="d-block me-2 my-1">
                            <img src="{{ asset('storage/payment-methods/american_express.svg')}}" alt="">
                        </div>
                        <div class="d-block me-2 my-1">
                            <img src="{{ asset('storage/payment-methods/paypal.svg')}}" alt="paypal">
                        </div>
                        <div class="d-block me-2 my-1">
                            <img src="{{ asset('storage/payment-methods/rupay.svg')}}" alt="rupay">
                        </div>
                        <div class="d-block my-1">
                            <img src="{{ asset('storage/payment-methods/visa.svg')}}" alt="visa">
                        </div>
                    </div>
                    <!--:/payment options-->
                </div>

                <div class="col-md-6 col-xl-4 text-md-end">
                    <!-- Links -->
                    <ul class="list-inline small mb-0">
                        <li class="list-inline-item me-3">
                            <a class="d-block" href="{{$store->facebook_url}}">
                                <i class="bx bxl-facebook fs-4"></i>
                            </a>
                        </li>
                        <li class="list-inline-item me-3">
                            <a class="d-block" href="{{$store->twitter_url}}">
                                <i class="bx bxl-twitter fs-4"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="d-block" href="{{$store->instagram_url}}">
                                <i class="bx bxl-instagram fs-4"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- End Links -->
                </div>


                {{-- <div class="col-md-6 col-xl-4 text-md-end">
                    <!-- Links -->
                    <ul class="list-inline small mb-0">
                        <li class="list-inline-item me-3">
                            <a class="d-block" href="#!"></a>
                        </li>
                        <li class="list-inline-item me-3">
                            <a class="d-block" href="#!"></a>
                        </li>
                        <li class="list-inline-item">
                            <a class="d-block" href="#!"></a>
                        </li>
                    </ul>
                    <!-- End Links -->
                </div> --}}
            </div>
        </div>
    </footer>

    <a href="#" class="toTop">
        <svg class="progress-circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="0, 251.2"></circle>
        </svg>
        <i class="bx bxs-up-arrow"></i>
    </a>

    @yield('customJS')


    <script>
        window.routes = {
            companyName: "{{ $store->cp_name }}"
            //Ostale rute ovde po potrebi
        };

    // Funkcija za postavljanje kolačića
    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    // Funkcija za dobijanje vrednosti kolačića
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Proveri da li je kolačić već postavljen
    if (!getCookie('unique_visitor')) {
    const apiUrl = 'https://freeipapi.com/api/json/';

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.ipAddress && data.countryName) {
                const country = data.countryName;
                const ip = data.ipAddress;

                // Enkodiraj podatke
                const obfuscatedCountry = btoa(country); // Enkodiraj zemlju
                const obfuscatedIp = btoa(ip); // Enkodiraj IP

                // Postavi kolačiće
                setCookie('unique_visitor_session', 'visited', 30); // Čuvaj 30 dana
                setCookie('unique_visitor_session1', obfuscatedCountry, 30); // Čuvaj 30 dana
                setCookie('unique_visitor_session2', obfuscatedIp, 30); // Čuvaj 30 dana

                // Pošalji zahtev na server da zabeleži posetu
                fetch('/track-visitor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ country: country, ip: ip })
                })
                .then(response => response.json())
                .catch(error => console.error('Greška:', error));
            } else {
                console.error('Greška u API odgovoru:', data.message);
            }
        })
        .catch(error => console.error('Greška:', error));
}

    document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                 // Pozovi funkciju za ažuriranje broja artikala prilikom učitavanja stranice
                updateCartCount();
                
            
                        
                window.addToCart = function(id) {
                var button = document.getElementById('add-to-cart-btn-' + id);
            
                var spinner = document.createElement('div');
                spinner.className = 'loader m-2'; // Koristimo novu klasu
                spinner.setAttribute('role', 'status');
                
                // Sakrij dugme dodavanjem klase 'd-none'
                button.classList.add('d-none');
                
                // Dodajemo spinner umesto dugmeta
                button.parentNode.insertBefore(spinner, button);
            
                $.ajax({
                    url: '{{ route("shop.addToCart") }}',
                    type: 'post',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === true) {
                            // Ažuriraj broj artikala u korpi nakon uspešnog dodavanja
                            updateCartCount();
                            // Prikaži toast notifikaciju
                            showToast('Proizvod je dodat u korpu');
                        } else {
                            alert(response.message);
                        }
            
                        setTimeout(function() {
                            // Prikaži dugme i ukloni spinner nakon 2 sekunde
                            button.classList.remove('d-none');
                            spinner.remove();
                        }, 1000);
                    }
                });
            };

            // Funkcija za ažuriranje broja artikala u korpi
            function updateCartCount() {
                $.ajax({
                    url: '{{ route("cart.count") }}',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var cartCountSpan = document.getElementById('cart-count');
                        if (cartCountSpan) {
                            cartCountSpan.textContent = response.count;
                            // Prikaži ili sakrij badge u zavisnosti od broja artikala u korpi
                            if (response.count > 0) {
                                cartCountSpan.classList.remove('d-none');
                            } else {
                                cartCountSpan.classList.add('d-none');
                            }
                        }
                    }
                });
            }
        });

            


                !function(C){C.fn.magnify=function(W){W=C.extend({src:"",speed:100,timeout:-1,touchBottomOffset:0,finalWidth:null,finalHeight:null,magnifiedWidth:null,magnifiedHeight:null,limitBounds:!1,mobileCloseEvent:"touchstart",afterLoad:function(){}},W);var t=this,x=C("html"),i=0,M=function(){clearTimeout(i),i=setTimeout(function(){t.destroy(),t.magnify(W)},100)};return this.destroy=function(){return this.each(function(){var t=C(this),i=t.prev("div.magnify-lens"),e=t.data("originalStyle");t.parent("div.magnify").length&&i.length&&(e?t.attr("style",e):t.removeAttr("style"),t.unwrap(),i.remove())}),C(window).off("resize",M),t},C(window).resize(M),this.each(function(){!function(t){var s=C(t),i=s.closest("a"),n={};for(var e in W)n[e]=s.attr("data-magnify-"+e.toLowerCase());var a=n.src||W.src||i.attr("href")||"";if(a){function o(){var t=l.offset();return B={top:s.offset().top-t.top+parseInt(s.css("border-top-width"))+parseInt(s.css("padding-top")),left:s.offset().left-t.left+parseInt(s.css("border-left-width"))+parseInt(s.css("padding-left"))},t.top+=B.top,t.left+=B.left,t}function r(){d.is(":visible")&&d.fadeOut(W.speed,function(){x.removeClass("magnifying").trigger("magnifyend")})}function f(t){if(u){if(t?(t.preventDefault(),v=t.pageX||t.originalEvent.touches[0].pageX,y=t.pageY||t.originalEvent.touches[0].pageY,s.data("lastPos",{x:v,y:y})):(v=s.data("lastPos").x,y=s.data("lastPos").y),b=v-N.left,w=y-N.top-W.touchBottomOffset,d.is(":animated")||(k<b&&b<c-k&&E<w&&w<u-E?d.is(":hidden")&&(x.addClass("magnifying").trigger("magnifystart"),d.fadeIn(W.speed)):r()),d.is(":visible")){var i="";if(h&&g){var e=-Math.round(b/c*h-m/2),n=-Math.round(w/u*g-p/2);if(W.limitBounds){var a=-Math.round((c-k)/c*h-m/2),o=-Math.round((u-E)/u*g-p/2);0<e?e=0:e<a&&(e=a),0<n?n=0:n<o&&(n=o)}i=e+"px "+n+"px"}d.css({top:Math.round(w-p/2)+B.top+"px",left:Math.round(b-m/2)+B.left+"px","background-position":i})}}else M()}var l,d,c,u,h,g,m,p,v,y,b,w,N,B,k=0,E=0;isNaN(+n.speed)||(W.speed=+n.speed),isNaN(+n.timeout)||(W.timeout=+n.timeout),isNaN(+n.finalWidth)||(W.finalWidth=+n.finalWidth),isNaN(+n.finalHeight)||(W.finalHeight=+n.finalHeight),isNaN(+n.magnifiedWidth)||(W.magnifiedWidth=+n.magnifiedWidth),isNaN(+n.magnifiedHeight)||(W.magnifiedHeight=+n.magnifiedHeight),"true"===n.limitBounds&&(W.limitBounds=!0),"function"==typeof window[n.afterLoad]&&(W.afterLoad=window[n.afterLoad]),/\b(Android|BlackBerry|IEMobile|iPad|iPhone|Mobile|Opera Mini)\b/.test(navigator.userAgent)?isNaN(+n.touchBottomOffset)||(W.touchBottomOffset=+n.touchBottomOffset):W.touchBottomOffset=0,s.data("originalStyle",s.attr("style"));var H=new Image;C(H).on({load:function(){s.css("display","block"),s.parent(".magnify").length||s.wrap('<div class="magnify"></div>'),l=s.parent(".magnify"),s.prev(".magnify-lens").length?l.children(".magnify-lens").css("background-image","url('"+a+"')"):s.before('<div class="magnify-lens loading" style="background:url(\''+a+"') 0 0 no-repeat\"></div>"),(d=l.children(".magnify-lens")).removeClass("loading"),c=W.finalWidth||s.width(),u=W.finalHeight||s.height(),h=W.magnifiedWidth||H.width,g=W.magnifiedHeight||H.height,m=d.width(),p=d.height(),N=o(),W.limitBounds&&(k=m/2/(h/c),E=p/2/(g/u)),h===H.width&&g===H.height||d.css("background-size",h+"px "+g+"px"),s.data("zoomSize",{width:h,height:g}),l.data("mobileCloseEvent",n.mobileCloseEvent||W.mobileCloseEvent),H=null,W.afterLoad(),d.is(":visible")&&f(),l.off().on({"mousemove touchmove":f,mouseenter:function(){N=o()},mouseleave:r}),0<=W.timeout&&l.on("touchend",function(){setTimeout(r,W.timeout)}),C("body").not(l).on("touchstart",r);var t=s.attr("usemap");if(t){var e=C("map[name="+t.slice(1)+"]");s.after(e),l.click(function(t){if(t.clientX||t.clientY){d.hide();var i=document.elementFromPoint(t.clientX||t.originalEvent.touches[0].clientX,t.clientY||t.originalEvent.touches[0].clientY);"AREA"===i.nodeName?i.click():C("area",e).each(function(){var t=C(this).attr("coords").split(",");if(b>=t[0]&&b<=t[2]&&w>=t[1]&&w<=t[3])return this.click(),!1})}})}i.length&&(i.css("display","inline-block"),!i.attr("href")||n.src||W.src||i.click(function(t){t.preventDefault()}))},error:function(){H=null}}),H.src=a}}(this)})}}(jQuery);
    })
    </script>

</body>
</html>