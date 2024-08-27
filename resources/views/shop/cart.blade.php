@extends('shop.layouts.app')




@section('content')
<style>
.product-image {
            max-width: 100px;
            width: auto;
            height: auto;
        }

        .product-details {
            display: flex;
            flex-direction: column;
            
        }

        .quantity-controls {
            display: flex;
            align-items: center;
        }

        .quantity {
            width: 50px;
            text-align: center;
        }

        .price {
            min-width: 100px;
        }

        .remove-item {
            align-self: flex-start;
            align-self: center; 
        }

        .empty-cart-icon {
            font-size: 200px;
        }

        .quantity-container {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 25px;
            overflow: hidden;
        }

        .quantity-button {
            background: none;
            border: none;
            padding: 7px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-button:focus {
            outline: none;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: none;
            font-size: 16px;
        }

        .quantity-input:focus {
            outline: none;
        }

        @media only screen and (max-width: 460px) {
            .cart-item {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .product-image {
                max-width: 80px;
            }

            .product-details {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }

            .quantity-controls {
                width: 100%;
                justify-content: space-between;
            }

            .quantity-input {
                font-size: 12px;
                width: 30px;
            }

            .quantity-button {
                padding: 5px;
                font-size: 14px;
            }

            .price {
                margin-top: 0;
               
            }

            
        }
</style>


<section class="position-relative">
    <div class="container position-relative pb-9 pb-lg-11 pt-5">
        <div class="row d-flex">
        @if(Cart::count() > 0)
            <div class="col-lg-8 mb-3">
                <div class="card bg-light text-dark border-0 rounded-0 p-2">
                    <div class="card-body">
                        <h2 class="card-title mb-5">Vaša korpa</h2>
                        @if(!empty($cartContent))
                            @foreach ($cartContent as $item)
                                @php
                                $hasDiscount = !empty($item->options->discount_price);
                                $productImage = $item->options->productImage->image;
                                @endphp
                            <div class="cart-item">
                                <div class="d-flex flex-row align-items-md-center">

                                    

                                    <div>
                                    @if (!empty($productImage))
                                        <img src="{{asset('uploads/product/small/'.$productImage)}}" alt="{{ $item->name }}" class="product-image mb-2 md-md-0">
                                    @endif
                                    </div>
                        
                                    <div class="d-flex flex-md-row flex-column flex-grow-1 ms-3 product-details">
                                        <div class="fw-semibold mb-1 mb-md-0 w-md-50 w-100 align-self-center">
                                            {{$item->name}}
                                        </div>
                                    
                                        <div class="d-flex align-items-center ms-0 ms-md-5 quantity-controls">
                                            <div class="quantity-container quanitity">
                                                <button class="quantity-button btn-minus text-dark sub" data-id="{{$item->rowId }}">-</button>
                                                <input type="text" class="quantity-input bg-light text-dark" value="{{$item->qty}}">
                                                <button class="quantity-button btn-plus text-dark add" data-id="{{$item->rowId}}">+</button>
                                            </div>
                                    
                                            <div class="fw-semibold ms-3 price">
                                                {{number_format($item->price * $item->qty, 0, ',', '.')}} {{$store->global_currency}}
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="align-self-center remove-item">
                                        <button class="border-0 bg-white remove-item ml-md-3 mt-3 mt-md-0" onclick="deleteItem('{{ $item->rowId }}')">
                                            <svg class="w-6 h-6 text-dark" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            @endforeach
                        @endif

                       
                          
                    </div>
                </div>
            </div>

            @php
                // Uklanjanje tačaka koje se koriste za razdvajanje hiljada
                $subtotal = str_replace(',', '', Cart::subtotal());
                // Pretvaranje u broj
                $subtotal = floatval($subtotal);
            @endphp

            <div class="col-lg-4">
                <div class="card bg-light text-dark border-0 rounded-0 p-2">
                    <div class="card-body">
                        <h5 class="card-title mb-5">Pregled porudžbine</h5>
                    
                        <div class="mb-1 d-flex align-items-center justify-content-between">
                            <p class="fs-6">Cena za online plaćanje:</p>
                            <p class="fs-6">{{ number_format($subtotal, 2, ',', '.') }} <span class="fs-6">{{$store->global_currency}}</span></p>
                        </div>
                    
                        <div class="mb-5 d-flex align-items-center justify-content-between">
                            <p class="fs-6">Popust:</p>
                            <p class="fs-6">0,00 {{$store->global_currency}}</p>
                        </div>
                    
                        <hr class="mb-5" />
                    
                        <div class="mb-5 d-flex align-items-center justify-content-between">
                            <p class="fs-5 fw-normal">Iznos kupovine:</p>
                            <p class="fs-4 fw-semibold">{{ number_format($subtotal, 2, ',', '.') }} <span class="fs-7">{{$store->global_currency}}</span></p>
                        </div>
                    
                        <div class="d-flex flex-column align-items-center">
                            <a href="{{ route('shop.checkout') }}" class="btn btn-warning shadow-none text-secondary rounded-5 w-100 mb-3">
                                Nastavite sa porudžbinom
                            </a>
                            <a href="{{ route('shop.homepage') }}" class="text-center link-hover-no-underline text-primary">Nastavite sa kupovinom</a>
                        </div>
                    </div>
                </div>
            </div>
            
        @else

            <div class="col col-12">
                <div class="d-flex text-dark justify-content-center flex-column align-items-center pt-10">
                    <span class="material-symbols-rounded empty-cart-icon mb-5">
                        shopping_cart
                    </span>
                    <h4>Vaša korpa je prazna</h4>
                    <a href="{{ route('shop.homepage') }}" class="link-primary link-hover-no-underline">Vratite se na početnu stranu</a>
                </div>
            </div>
        
        @endif 
        </div>
        

    </div>
</section>
@endsection

@section('customJS')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.add').click(function(){
                var qtyElement = $(this).siblings('.quantity-input'); // qty Input 
                var qtyValue = parseInt(qtyElement.val());
                if (qtyValue < 20) {
                    qtyElement.val(qtyValue + 1);
                    var rowId = $(this).data('id');
                    var newQty = qtyElement.val();
                    updateCart(rowId, newQty)
                }            
            });
            
            $('.sub').click(function(){
                var qtyElement = $(this).siblings('.quantity-input'); 
                var qtyValue = parseInt(qtyElement.val());
                if (qtyValue > 1) {
                    qtyElement.val(qtyValue - 1);
                    var rowId = $(this).data('id');
                    var newQty = qtyElement.val();
                    updateCart(rowId, newQty)
                }        
            });

    function updateCart(rowId, qty) {
        $.ajax({
            url: '{{ route("shop.updateCart") }}',
            type: 'post',
            data: {rowId:rowId, qty:qty},
            dataType: 'json',
            success: function(response) {
                window.location.href= "{{ route('shop.cart') }}"
            }
        })
    }
    })

    function deleteItem(rowId) {

$.ajax({
url: '{{ route("shop.deleteItem.cart") }}',
type: 'post',
data: {rowId:rowId},
dataType: 'json',
success: function(response) {
    window.location.href= "{{ route('shop.cart') }}"
}
})

}
</script>
@endsection