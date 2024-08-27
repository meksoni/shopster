@extends('shop.layouts.app')



@section('content')

<style>
    #overlay {
        visibility: hidden;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(5px);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .loader {
      width: 50px;
      aspect-ratio: 1;
      border-radius: 50%;
      border: 8px solid #0000;
      border-right-color: #ffa50097;
      position: relative;
      animation: l24 1s infinite linear;
    }
    .loader:before,
    .loader:after {
      content: "";
      position: absolute;
      inset: -8px;
      border-radius: 50%;
      border: inherit;
      animation: inherit;
      animation-duration: 2s;
    }
    .loader:after {
      animation-duration: 4s;
    }
    @keyframes l24 {
      100% {transform: rotate(1turn)}
    }


</style>

<div id="overlay">
    <div class="loader"></div>
</div>

<section class="position-relative">
    <div class="container pb-9 pb-lg-12 pt-7 position-relative">
        <div class="row">
            <div class="col-lg-7 col-md-12 mb-5">
                <div class="card bg-light border-0 text-dark p-4">
                    <h4 class="">Porudžbina</h4>
                    <hr/>
                    <h6 class="mb-3 text-underline">Izaberite formu za porudžbinu</h6>
                    <div class="row align-items-center mb-3 mb-md-3">
                        <div class="col-6">
                            <button id="btnFizickoLice" class="btn btn-sm btn-outline-gray  text-dark w-100 p-2  border border-gray-800 text-capitalize fw-normal gap-2 fs-7 d-flex align-items-center justify-content-center">
                                <span class="material-symbols-rounded">
                                    person
                                </span>
                                Fizičko lice
                            </button>
                        </div>
                        <div class="col-6">
                            <button id="btnPravnoLice" class="btn btn-sm w-100 p-2 btn-outline-gray text-dark  border border-gray-800 text-capitalize gap-2 fs-7 fw-normal d-flex align-items-center justify-content-center">
                                <span class="material-symbols-rounded">
                                    groups
                                </span>
                                Pravno lice
                            </button>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <form action="" name="formFizickoLice" method="POST" id="formFizickoLice">
                            <input type="hidden" name="order_type" value="user">
                            <div class="row">
                                <div class="col-md-6 ">
                                    <label class="form-label" for="full_name">Ime i prezime*</label>
                                    <input  type="text" id="full_name" class="form-control text-dark required form-control-sm" name="full_name">
                                    <p></p>
                                </div>

                                <div class="col-md-6 ">
                                    <label class="form-label" for="email">E-mail*</label>
                                    <input  type="text" id="email" class="form-control text-dark required form-control-sm" name="email">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="address">Adresa dostave*</label>
                                    <input  type="text" id="address" class="form-control text-dark required form-control-sm" name="address">
                                    <p></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="phone_number">Telefon*</label>
                                    <input  type="text" id="phone_number" class="form-control text-dark required form-control-sm" name="phone_number">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="city">Grad*</label>
                                    <input  type="text" id="city" class="form-control text-dark required form-control-sm" name="city">
                                    <p></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="zip_code">Poštanski broj*</label>
                                    <input  type="text" id="zip_code" maxlength="5" class="form-control text-dark required form-control-sm" name="zip_code">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="note_to_seller">Napomena</label>
                                    <textarea id="note_to_seller" class="form-control text-dark" name="note_to_seller"></textarea>
                                    <p></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="delivery_method">Preuzimanje</label>
                                    <select id="delivery_method" class="form-select form-select-sm text-dark" aria-label=".form-select-sm delivery_method" name="delivery_method">
                                        <option value="address">Kurirskom službom - 500 RSD</option>
                                        <option value="store">Preuzimanje u prodavnici</option>
                                    </select>
                                    <p></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="payment_method">Plaćanje</label>
                                    <select id="payment_method" class="form-select form-select-sm text-dark" aria-label=".form-select-sm payment_method" name="payment_method">
                                        <option value="cash_on_delivery">Kuriru - prilikom preuzimanja</option>
                                        <option value="bank_transfer">IPS / Nalog za prenos</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-md bg-warning text-dark text-capitalize fw-normal">
                                        Poručite
                                    </button>
                                </div>
                            </div>

                        </form>

                        <form action="" name="formPravnoLice" id="formPravnoLice" class="d-none">
                            <input type="hidden" name="order_type" value="company">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="full_name">Ime i prezime*</label>
                                    <input  type="text" id="full_name" class="form-control text-dark required form-control-sm" name="full_name">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="email">E-mail*</label>
                                    <input  type="text" id="email" class="form-control text-dark required form-control-sm" name="email">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="address">Adresa dostave*</label>
                                    <input  type="text" id="address" class="form-control text-dark required form-control-sm" name="address">
                                    <p></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="phone_number">Telefon*</label>
                                    <input  type="text" id="phone_number" class="form-control text-dark required form-control-sm" name="phone_number">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="city">Grad*</label>
                                    <input  type="text" id="city" class="form-control text-dark required form-control-sm" name="city">
                                    <p></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="zip_code">Poštanski broj*</label>
                                    <input  type="text" id="zip_code" maxlength="5" class="form-control text-dark required form-control-sm" name="zip_code">
                                    <p id="error-message"></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="company_name">Ime kompanije*</label>
                                    <input  type="text" id="company_name" class="form-control text-dark required form-control-sm" name="company_name">
                                    <p></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="company_owner">Predstavnik kompanije*</label>
                                    <input  type="text" id="company_owner" class="form-control text-dark required form-control-sm" name="company_owner">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="company_pib">Poreski identifikacioni broj (PIB)*</label>
                                    <input  type="text" id="company_pib" class="form-control text-dark required form-control-sm" name="company_pib">
                                    <p></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="bank_account_number">Broj računa</label>
                                    <input  type="text" id="bank_account_number" data-inputmask="'mask':'999 [9999999999999] 99'" class="form-control text-dark form-control-sm" name="bank_account_number">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="note_to_seller">Napomena</label>
                                    <textarea id="note_to_seller" class="form-control text-dark" name="note_to_seller"></textarea>
                                    <p></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="delivery_method">Preuzimanje</label>
                                    <select id="delivery_method" class="form-select form-select-sm text-dark" aria-label=".form-select-sm delivery_method" name="delivery_method">
                                        <option value="address">Kurirskom službom - 500 RSD</option>
                                        <option value="store">Preuzimanje u prodavnici</option>
                                    </select>
                                    <p></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="payment_method">Plaćanje</label>
                                    <select id="payment_method" class="form-select form-select-sm text-dark" aria-label=".form-select-sm payment_method" name="payment_method">
                                        <option value="cash_on_delivery">Kuriru - prilikom preuzimanja</option>
                                        <option value="bank_transfer">IPS / Nalog za prenos</option>
                                    </select>
                                    <p></p>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-md bg-warning text-dark text-capitalize fw-normal">
                                        Poručite
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            @php
                // Uklanjanje tačaka koje se koriste za razdvajanje hiljada
                $subtotal = str_replace(',', '', Cart::subtotal());
                // Pretvaranje u broj
                $subtotal = floatval($subtotal);
            @endphp

            <div class="col-lg-5 col-md-12 mb-5">
                <div class="card bg-light text-dark border-0 p-3">
                    <div class="card-body">
                        <h5 class="card-title mb-5">Pregled narudžbine</h5>
                        <hr/>
                        <div class="mb-1 d-flex align-items-center justify-content-between">
                            <p class="fs-6">Cena za online plaćanje:</p>
                            <p class="fs-6">{{ number_format($subtotal, 2, ',', '.') }} <span class="fs-6">{{$store->global_currency}}</span></p>
                        </div>

                        <div class="mb-5 d-flex align-items-center justify-content-between">
                            <p class="fs-6">Popust:</p>
                            <p class="fs-6">0,00 {{$store->global_currency}}</p>
                        </div>

                        <hr class="mb-5" />

                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fs-5 fw-normal">Iznos kupovine:</p>
                            <p class="fs-4 fw-semibold">{{ number_format($subtotal, 2, ',', '.') }} <span class="fs-7">{{$store->global_currency}}</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('customJS')

<script src="{{ asset('assets/vendor/node_modules/js/inputmask.min.js')}}"></script>
<script>
    Inputmask().mask(document.querySelectorAll("[data-inputmask]"));
</script>

<script>
document.getElementById("zip_code").addEventListener("input", function (e) {
    var value = this.value;
    // Uklanja sve što nije cifra
    this.value = value.replace(/[^0-9]/g, "");
});

document.getElementById("bank_account_number").addEventListener("input", function (e) {
    var value = this.value;
    // Uklanja sve što nije cifra
    this.value = value.replace(/[^0-9]/g, "");
});

document.getElementById("company_pib").addEventListener("input", function (e) {
    var value = this.value;
    // Uklanja sve što nije cifra
    this.value = value.replace(/[^0-9]/g, "");
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('formFizickoLice').addEventListener('submit', function(event) {
    document.getElementById('loader').style.display = 'block';
  });

  document.getElementById('formPravnoLice').addEventListener('submit', function(event) {
    document.getElementById('loader').style.display = 'block';
  });

    $("#formFizickoLice").removeClass("d-none");
    $("#btnFizickoLice").addClass("active");

    // Klik na dugme za fizičko lice
    $("#btnFizickoLice").click(function() {
        $("#formFizickoLice").removeClass("d-none");
        $("#formPravnoLice").addClass("d-none");
        $(this).addClass("active");
        $("#btnPravnoLice").removeClass("active");
    });

    // Klik na dugme za pravno lice
    $("#btnPravnoLice").click(function() {
        $("#formPravnoLice").removeClass("d-none");
        $("#formFizickoLice").addClass("d-none");
        $(this).addClass("active");
        $("#btnFizickoLice").removeClass("active");
    });

        // Funkcija za proveru da li su sva obavezna polja popunjena
        function checkRequiredFields(form) {
            let allFilled = true;
            $(form).find('.required').each(function() {
                if (!$(this).val()) {
                    allFilled = false;
                }
            });
            $(form).find('button[type="submit"]').prop('disabled', !allFilled); // Omogućava ili onemogućava dugme
        }

        // Pratite promene u obaveznim poljima za formu fizičkog lica
        $("#formFizickoLice .required").on('input change', function() {
            checkRequiredFields('#formFizickoLice');
        });

        // Pratite promene u obaveznim poljima za formu pravnog lica
        $("#formPravnoLice .required").on('input change', function() {
            checkRequiredFields('#formPravnoLice');
        });

        // Inicijalna provera pri učitavanju stranice
        checkRequiredFields('#formFizickoLice');
        checkRequiredFields('#formPravnoLice');

        // FORMULAR ZA FIZIČKO LICE
        $("#formFizickoLice").submit(function(event) {
            event.preventDefault();
            $('#overlay').css('visibility', 'visible'); // Prikaži loader
            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: '{{ route("shop.processCheckout") }}',
                type: 'POST',
                data: $(this).serialize(), // Serialize podatke iz forme za fizička lica
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;

                    if (response.status === false) {
                        $('#overlay').css('visibility', 'hidden');  
                        $.each(errors, function(key, value) {
                            $("#" + key).addClass('is-invalid')
                                        .siblings("p")
                                        .addClass('invalid-feedback')
                                        .html(value);
                        });
                    } else {
                        $('#overlay').css('visibility', 'hidden');  
                        var orderId = response.orderId;
                        var redirectUrl = "{{ route('shop.successPage', ':orderId') }}";
                        redirectUrl = redirectUrl.replace(':orderId', orderId);
                        window.location.href = redirectUrl;
                    }
                },
                error: function(xhr, status, error) {
                    $('#overlay').css('visibility', 'hidden');  
                    console.error(xhr.responseText);
                    alert('Došlo je do greške prilikom slanja zahteva.');
                }
            });
        });

        // FORMULAR ZA PRAVNO LICE
        $("#formPravnoLice").submit(function(event) {
            event.preventDefault();
            $('#overlay').css('visibility', 'visible'); // Prikaži loader
            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: '{{ route("shop.processCompanyOrder") }}', // Endpoint za pravna lica
                type: 'POST',
                data: $(this).serialize(), // Serialize podatke iz forme za pravna lica
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;

                    if (response.status === false) {
                        $('#overlay').css('visibility', 'hidden');  
                        $.each(errors, function(key, value) {
                            $("#" + key).addClass('is-invalid')
                                        .siblings("p")
                                        .addClass('invalid-feedback')
                                        .html(value);
                        });
                    } else {
                        $('#overlay').css('visibility', 'hidden');  
                        var orderId = response.orderId;
                        var redirectUrl = "{{ route('shop.successPage', ':orderId') }}";
                        redirectUrl = redirectUrl.replace(':orderId', orderId);
                        window.location.href = redirectUrl;
                    }
                },
                error: function(xhr, status, error) {
                    $('#overlay').css('visibility', 'hidden');  
                    console.error(xhr.responseText);
                    alert('Došlo je do greške prilikom slanja zahteva.');
                }
            });
        });
    });
</script>


@endsection