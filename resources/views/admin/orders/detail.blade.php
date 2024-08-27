@extends('admin.layouts.app')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #invoicePrint, #invoicePrint * {
            visibility: visible;
        }

        #invoicePrint {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: auto;
        }

        .content {
            max-width: none;
            margin: 0;
            padding: 0;
        }

        .card {
            margin: 0;
            border: none;
            box-shadow: none;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
        }

        .table thead th {
            border-top: 1px solid #dee2e6;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }
    }
</style>

<div class="container-fluid px-4">
@include('admin.components.messages')  
</div>

<div class="content pb-0 pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid position-relative px-0">
  
      <!--Widget row Title-->
      <div class="d-flex mb-4 align-items-center">
          <div class="flex-grow-1 border-top border-gray"></div>
          <h5 class="flex-shrink-0 mb-0 px-3">Detalji Porudžbine</h5>
          <div class="flex-grow-1 border-top border-gray"></div>
      </div>

      <div class="row">
    <div class="col-lg-12 mb-2">
        <div class="card card-body d-flex flex-column flex-lg-row align-items-center justify-content-between p-3">
            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                <span class="fw-normal">IP Adresa poručioca: <span class="text-primary">{{ $order->ip_address ?? 'Nepoznato' }}</span></span><br>
                <span class="fw-normal">Zemlja poručioca: <span class="text-primary">{{ $order->country ?? 'Nepoznato'}}</span></span>
            </div>

            <div class="col-12 col-lg-6 d-flex align-items-center justify-content-end">
                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    @method('PUT')
                    <div class="form-group me-2">
                        <select name="status" id="status" class="form-select w-auto">
                            <option value="">-- Status porudžbine --</option>
                            @foreach($orderStatus as $value => $label)
                                <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Ažuriraj</button>
                </form>
            </div>
        </div>
    </div>
</div>
    

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-2 card-body" id="invoicePrint">
                    <div class="d-flex justify-content-sm-between flex-column flex-sm-row mb-5">
                        <div class="mb-4 mb-sm-0">
                            <h3 class="mb-3">Porudžbina #{{ $order->id }}</h3>
                            <ul class="list-unstyled d-table mb-0">
                                <li class="d-flex align-items-center border-bottom border-dashed pb-1 mb-1">
                                    <span class="pe-3 small">Narudžbina broj :</span>
                                    <span class="mb-0 flex-grow-1 text-end"># {{ $order->id }}</span>
                                </li>
                                <li class="d-flex align-items-center border-bottom border-dashed pb-1 mb-1">
                                    <span class="pe-3 small">Datum porudzbine :</span>
                                    <span class="mb-0 flex-grow-1 text-end">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y - H:i:s') }} h</span>
                                </li>
                                <li class="d-flex align-items-center border-bottom border-dashed pb-1 mb-1">
                                    <span class="pe-3 small">Ukupna cena :</span>
                                    <span class="mb-0  flex-grow-1 text-end">{{ number_format($order->grand_total,2,',','.') }} {{$store->global_currency}}</span>
                                </li>

                                <li class="d-flex align-items-center border-bottom border-dashed pb-1 mb-1">
                                    <span class="pe-3 small">Preuzimanje :</span>
                                    <span class="mb-0  flex-grow-1 text-end">
                                        @if($order->orderable->delivery_method == 'store')
                                            Preuzimanje u prodavnici
                                        @elseif($order->orderable->delivery_method == 'address')
                                            Kurirskom službom
                                        @endif
                                    </span>
                                </li>

                                <li class="d-flex align-items-center">
                                    <span class="pe-3 small">Plaćanje :</span>
                                    <span class="mb-0  flex-grow-1 text-end">
                                        @if($order->orderable->payment_method == 'cash_on_delivery')
                                            Kuriru pri dostavi
                                        @elseif($order->orderable->payment_method == 'bank_transfer')
                                            Bankovni transfer
                                        @endif
                                    </span>
                                </li>

                            </ul>
                        </div>

                        <div class="text-sm-end">
                            @if($order->orderable_type === \App\Models\UserOrder::class)
                            <div class="mb-3">
                                <small class="text-body-secondary">Poručilac</small>
                                <p class="mb-0">
                                    <span class="text-body-secondary fs-6">Ime i Prezime: </span>{{ $order->orderable->full_name }} <br>
                                    <span class="text-body-secondary fs-6">Adresa: </span>{{ $order->orderable->address }}, {{ $order->orderable->city }}, {{ $order->orderable->zip_code }}<br>
                                    <span class="text-body-secondary fs-6">Kontakt telefon: </span>{{ $order->orderable->phone_number }}<br>
                                    <span class="text-body-secondary fs-6">Kontakt email: </span><a href="mailto:{{$order->orderable->email}}" class="text-primary">{{ $order->orderable->email }}</a>
                                </p>
                            </div>
                                
                            @elseif ($order->orderable_type === \App\Models\CompanyOrder::class)

                            <div>
                                <small class="text-body-secondary">Poručilac</small>
                                <p class="mb-0">
                                    <span class="text-body-secondary fs-6">Ime i Prezime: </span>{{ $order->orderable->full_name }} <br>
                                    <span class="text-body-secondary fs-6">Adresa: </span>{{ $order->orderable->address }}, {{ $order->orderable->city }}, {{ $order->orderable->zip_code }}<br>
                                    <span class="text-body-secondary fs-6">Kontakt telefon: </span>{{ $order->orderable->phone_number }}<br>
                                    <span class="text-body-secondary fs-6">Kontakt email: </span><a href="mailto:{{$order->orderable->email}}" class="text-primary">{{ $order->orderable->email }}</a>
                                </p>
                                <hr />
                                <small class="text-body-secondary">Podaci firme</small>
                                <p class="mb-0">
                                    <span class="text-body-secondary fs-6">Naziv kompanije: </span>{{ $order->orderable->company_name }}<br>
                                    <span class="text-body-secondary fs-6">Vlasnik kompanije: </span>{{ $order->orderable->company_owner }}<br>
                                    <span class="text-body-secondary fs-6">PIB: </span>{{ $order->orderable->company_pib }}<br>
                                    @if(!empty($order->orderable->bank_account_number))
                                    <span class="text-body-secondary fs-6">Bankovni račun: </span>{{ $order->orderable->bank_account_number }}<br>
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>

                    </div>
                    @if(!empty($order->orderable->note_to_seller))
                    <div class="row mb-5 border-bottom py-2">
                        <div class="col-12">
                            <div class="d-flex flex-column align-items-start">
                                <span class="pe-3 text-body-secondary">Napomena za prodavca/kurira :</span>
                                <span>
                                    {{ $order->orderable->note_to_seller }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 300px;"><h6>Artikl</h6></th>
                                            <th><h6>Cena</h6></th>
                                            <th><h6>Količina</h6></th>
                                            <th class="text-end" style="min-width:100px;"><h6>Ukupna Cena</h6></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPrice = 0;
                                        @endphp
                                        @foreach($orderItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ number_format($item->price,2,',','.') }}</td>
                                            <td>{{ $item->qty}}</td>
                                            <td class="text-end">{{ number_format($item->price*$item->qty,2,',','.') }} {{$store->global_currency}}</td>
                                        </tr>
                                        @php
                                            $totalPrice += $item->price * $item->qty;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <table class="table table-borderless mb-4">
                                    <tbody>
                                        <tr>
                                            <td>Suma</td>
                                            <td class="text-end ">{{ number_format($totalPrice, 2, ',', '.') }} {{$store->global_currency}}</td>
                                        </tr>
                                        <tr>
                                            <td>Dostava</td>
                                            <td class="text-end ">+ {{number_format($order->shipping, 2,',','.')}} {{$store->global_currency}}</td>
                                        </tr>
                                        <tr>
                                            <td>Popust</td>
                                            <td class="text-end ">- 0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Ukupno za naplatu</td>
                                            <td class="text-end ">{{ number_format($order->grand_total,2) }} {{$store->global_currency}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row d-print-none justify-content-between align-items-center">
                        <div class="col-auto me-auto">
                            <a href="{{ route('orders.index')}}" class="btn btn-secondary">Nazad</a>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-primary" href="javascript:window.print()">Štampaj
                                <span class="material-symbols-rounded align-middle ms-1 fs-5">
                                    print
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('customJS')
@endsection
