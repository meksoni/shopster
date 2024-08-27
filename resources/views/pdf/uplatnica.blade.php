<html>
    <head>
        <meta charset="UTF-8">
        <title>IPS / Nalog za prenos</title>
    </head>

<style>
.payment-slip{
  background-color:; 
  overflow:hidden; 
  width: 210mm;
  height:99mm;
  position: relative;
  margin:0 auto;
}
.bg-image{
  width:210mm;
  height:99mm;
}
.field{
  position:absolute;
  font-size: 14px;
  background-color:white;
}
.field1{
    left: 30px;
    top: 45px;
    width: 332px;
    height: 52px;
}
.field2{
    left: 30px;
    top: 128px;
    width: 332px;
    height: 52px;
}
.field3{
    left: 30px;
    top: 209px;
    width: 332px;
    height: 52px;
}
.field4{
    left: 26px;
    top: 274px;
    width: 204px;
    height: 16px;
}
.field5{
    left: 188px;
    top: 312px;
    width: 180px;
    height: 16px;
}
.field6{
    left: 425px;
    top: 312px;
    width: 130px;
    height: 16px;
}
.field7{
    left: 428px;
    top: 60px;
    width: 47px;
    height: 16px;
    text-align: center;
}
.field8{
    left: 497px;
    top: 60px;
    width: 47px;
    height: 16px;
    text-align: center;
}
.field9{
    left: 574px;
    top: 60px;
    width: 197px;
    height: 16px;
    text-align: center;
}
.field10{
    left: 430px;
    top: 114px;
    width: 341px;
    height: 16px;
    text-align: center;
}
.field11{
    left: 428px;
    top: 161px;
    width: 36px;
    height: 16px;
    text-align: center;
}
.field12{
    left: 498px;
    top: 161px;
    width: 272px;
    height: 16px;
}
.field13{
    left: 430px;
    top: 208px;
    width: 341px;
    height: 16px;
    text-align: center;
}
.field14{
    left: 428px;
    top: 258px;
    width: 36px;
    height: 16px;
    text-align: center;
}
.field15{
    left: 498px;
    top: 258px;
    width: 272px;
    height: 16px;
}
</style>

<body>

    <div class="payment-slip">
        @if($order->orderable_type === \App\Models\UserOrder::class)
            <!-- HTML kod za UserOrder -->
            <img class="bg-image" src="https://elektronskiobrazac.com/img/nalog%20za%20prenos%20obrazac.jpg" alt="">
            <div class="field field1">
                {{ $order->orderable->full_name }}<br>
                {{$order->orderable->address}}, {{ $order->orderable->city }}, {{$order->orderable->zip_code}}
            </div>
            <div class="field field2">Online kupovina IT opreme, Porudžbina #{{$order->id}}</div>
            <div class="field field3">
                {{$store->cp_name}}<br>
                {{$store->cp_address}}, {{$store->cp_city}}, {{$store->cp_zip}}
            </div>
            <div class="field field4"></div>
            <div class="field field5"></div>
            <div class="field field6"></div>
            <div class="field field7">189</div>
            <div class="field field8">{{$store->global_currency }}</div>
            <div class="field field9">{{ number_format($order->grand_total, 2, ',', '.') }}</div>
            <div class="field field10"></div>
            <div class="field field11"></div>
            <div class="field field12"></div>
            <div class="field field13">{{ $store->cp_bank_account }}</div>
            <div class="field field14"></div>
            <div class="field field15">{{ $order->id }}/{{ $order->created_at->format('Y') }}</div>
        @elseif($order->orderable_type === \App\Models\CompanyOrder::class)
            <!-- HTML kod za CompanyOrder -->
            <img class="bg-image" src="https://elektronskiobrazac.com/img/nalog%20za%20prenos%20obrazac.jpg" alt="">
            <div class="field field1">
                {{ $order->orderable->full_name }}<br>
                {{$order->orderable->address}}, {{ $order->orderable->city }}, {{$order->orderable->zip_code }}<br>
                {{ $order->orderable->company_name }}, {{ $order->orderable->company_pib }}
            </div>
            <div class="field field2">Online kupovina IT opreme, Porudžbina #{{$order->id}}</div>
            <div class="field field3">
                {{$store->cp_name}}<br>
                {{$store->cp_address}}, {{$store->cp_city}}, {{$store->cp_zip}}
            </div>
            <div class="field field4"></div>
            <div class="field field5"></div>
            <div class="field field6"></div>
            <div class="field field7">189</div>
            <div class="field field8">{{$store->global_currency }}</div>
            <div class="field field9">{{ number_format($order->grand_total, 2, ',', '.') }}</div>
            <div class="field field10">{{$order->orderable->bank_account_number}}</div>
            <div class="field field11"></div>
            <div class="field field12"></div>
            <div class="field field13">{{ $store->cp_bank_account }}</div>
            <div class="field field14"></div>
            <div class="field field15">{{ $order->id }}/{{ $order->created_at->format('Y') }}</div>
        @endif
    </div>
    
    <div>
        @if($qrCode)
            <div style="text-align: center; margin-top: 20px;">
                <img src="{{ $qrCode }}" alt="QR Code">
            </div>
        @else
            <p>QR kod nije dostupan.</p>
        @endif
    </div>

</body>
</html>