@extends('shop.layouts.app')


@section('content')
<section class="position-relative">
    <div class="container pt-14 pb-9 pb-lg-11">
        <div class="row pt-lg-7 justify-content-center text-center">
            <div class="col-xl-8">
               <div class="width-10x height-10x rounded-circle position-relative bg-success text-white flex-center mb-4">
                   <i class="bx bx-check lh-1 display-4 fw-normal"></i>
               </div>
               <h1 class="mb-3 text-dark">Primili smo vaš zahtev za porudžbinu</h1>
               <h3 class="mb-3 text-dark">Broj vaše porudžbine je <span class="fw-bold">{{$order->id}}</span></h3>
               <a href="{{ route('shop.homepage')}}" class="btn btn-outline-primary btn-sm px-4 px-lg-5">
                   Nastavite kupovinu
                </a>
            </div>
        </div>
    </div>
</section>
@endsection