@component('mail::message')
# Imate novu porudžbinu na sajtu

Obaveštenje o novoj porudžbini sa ID - {{ $orderId }}.<br>

<a class="fs-6" href="https://shop.alfasoft.rs/admin/orders/{{ $orderId }}"> Ulogujte se i pogledajte detalje porudžbine</a> <br>

Hvala, {{ config('app.name') }} Administrator  <br>

<small>
    Ukoliko postoji problem sa ovom porudžbinom. 
    Kontaktirajte administratora vaše web stranice.
</small>
@endcomponent