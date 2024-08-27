<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nova porudzbina | AlfaSoft Webshop</title>
    <style>
.table-responsive {
    margin: 20px 0;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background-color: #f9f9f9; /* svetla pozadina */
}

.table th, .table td {
    padding: 5px;
    text-align: left;
    border: 1px solid #dee2e6; /* svetlosiva ivica */
}

.table thead {
    background-color: #f1f1f1; /* bela pozadina za zaglavlja */
    color: #2e2e2e; /* plavi tekst */
}

.table thead th {
    font-weight:normal;
}

.table tbody tr:hover {
    background-color: #f1f1f1; /* svetla pozadina pri prelazu miša */
}

.table-borderless tbody tr td {
    border: none; /* bez ivica za drugu tabelu */
}

.table-borderless tbody tr td:first-child {
    font-weight: bold; /* podebljan tekst za opisne stavke */
}

.text-end {
    text-align: right; /* desno poravnanje */
}

.mb-4 {
    margin-bottom: 1.5rem; /* razmak na dnu */
}
     
.table-bordered {
    width: 100%; /* Puna širina */
    border-collapse: collapse; /* Eliminisanje razmaka između ivica */
}

.table-bordered th, .table-bordered td {
    padding: 10px; /* Prilagodite padding po potrebi */
}

@media (max-width: 576px) { /* Mobilni uređaji */
    .table-bordered th, .table-bordered td {
        font-size: 14px; /* Smanjenje font-size za mobilne uređaje */
    }
}

.order-details ul {
    list-style-type: none;
    padding: 0;
}

.order-details li {
    border-bottom: 1px dashed #777;
    padding-bottom: 5px;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
}

.order-details h3 {
    margin-bottom: 10px;
}

.order-details .flex-container {
    display: flex;
    flex-direction: row;
}

.order-details .flex-container > div {
    margin-bottom: 20px;
}

.order-details .text-end {
    text-align: right;
}

.order-details .text-start {
    text-align: left;
}



@media (min-width: 768px) {
    .order-info {
        border-right: 1px solid #ccc; /* Svetlija boja */
        padding-right: 70px; /* Dodaje padding desno */
        margin-right: 20px;

    }

    .info-naslov {
        font-weight: 500;
    }

    .order-info ul{
        width: 300px;
    }

    .customer-info {
        margin-left: 50px;
    }
}

@media (max-width: 767px) {
    .order-info, .customer-info {
        margin: 0 0 20px 0;
    }

    .order-info {
        border-bottom: 1px solid #ccc; /* Svetlija boja */
    }

    .order-details .flex-container {
    display: flex;
    flex-direction: column;
}
}

.order-info ul li {
    border-bottom: 1px dashed #777;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.order-info ul li:last-child {
    border-bottom: none;
}


    </style>
</head>
<body>
    @php
        $order = $mailData['order'];
    @endphp
    <div class="container-fluid position-relative px-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-2 card-body">
                    <div class="order-details">
                        <div class="flex-container">
                            <div class="order-info mb-4 mb-sm-0">
                                <h3 class="mb-3">Detalji porudžbine #{{ $order->id }}</h3>
                                <ul class="list-unstyled d-table mb-0">
                                    <li style="display: flex;">
                                        <span class="" style="font-weight: bold; float:left; text-align:left;">Porudžbina broj:</span>
                                        <span class="mb-0 text-end" style="text-align: right; float:right;">{{ $order->id }}</span>
                                    </li>
                                    <li class="d-flex align-items-center" style="display: flex;">
                                        <span class="pe-3 small text-start" style="font-weight: bold; float:left; text-align:left;">Datum:</span>
                                        <span class="mb-0 flex-grow-1  text-end" style="text-align: right; float:right;">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y - H:i:s') }} h</span>
                                    </li>
                                    <li class="d-flex align-items-center" style="display: flex;">
                                        <span class="pe-3 small text-start" style="font-weight: bold; float:left; text-align:left;">Ukupna cena:</span>
                                        <span class="mb-0  flex-grow-1  text-end" style="text-align: right; float:right;">{{ number_format($order->grand_total,2,',','.') }} {{$store->global_currency}}</span>
                                    </li>
                                    <li class="d-flex align-items-center" style="display: flex;">
                                        <span class="pe-3 small text-start" style="font-weight: bold; float:left; text-align:left;">Preuzimanje:</span>
                                        <span class="mb-0  flex-grow-1  text-end" style="text-align: right; float:right;">
                                            @if($order->orderable->delivery_method == 'store')
                                                Preuzimanje u prodavnici
                                            @elseif($order->orderable->delivery_method == 'address')
                                                Kurirskom službom
                                            @endif
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="pe-3 small" style="font-weight: bold; float:left; text-align:left;">Plaćanje :</span>
                                        <span class="mb-0  flex-grow-1 text-end" style="text-align: right; float:right;">
                                            @if($order->orderable->payment_method == 'cash_on_delivery')
                                                Kuriru pri dostavi
                                            @elseif($order->orderable->payment_method == 'bank_transfer')
                                                Bankovni transfer
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="customer-info text-sm-end">
                                @if($order->orderable_type === \App\Models\UserOrder::class)
                                <h3 class="mb-3"></h3>
                                <div class="mb-3">
                                    <small class="text-body-secondary">Poručilac</small>
                                    <p class="mb-0">
                                        <span class="text-body-secondary fs-6 info-naslov">Ime i Prezime: </span>{{ $order->orderable->full_name }} <br>
                                        <span class="text-body-secondary fs-6 info-naslov">Adresa: </span>{{ $order->orderable->address }}, {{ $order->orderable->city }}, {{ $order->orderable->zip_code }}<br>
                                        <span class="text-body-secondary fs-6 info-naslov">Kontakt telefon: </span>{{ $order->orderable->phone_number }}<br>
                                        <span class="text-body-secondary fs-6 info-naslov">Kontakt email: </span><a href="mailto:{{$order->orderable->email}}" class="text-primary">{{ $order->orderable->email }}</a><br>
                                        <span class="text-body-secondary fs-6 info-naslov">
                                            Napomena:<br>
                                            {{ $order->orderable->note_to_seller }}
                                        </span>
                                    </p>
                                </div>
                                @elseif ($order->orderable_type === \App\Models\CompanyOrder::class)
                                <h3 class="mb-3"></h3>
                                <div class="mb-3">
                                    <small class="text-body-secondary">Poručilac</small>
                                    <p class="mb-0">
                                        <span class="text-body-secondary fs-6">Ime i Prezime: </span>{{ $order->orderable->full_name }} <br>
                                        <span class="text-body-secondary fs-6">Adresa: </span>{{ $order->orderable->address }}, {{ $order->orderable->city }}, {{ $order->orderable->zip_code }}<br>
                                        <span class="text-body-secondary fs-6">Kontakt telefon: </span>{{ $order->orderable->phone_number }}<br>
                                        <span class="text-body-secondary fs-6">Kontakt email: </span><a href="mailto:{{$order->orderable->email}}" class="text-primary">{{ $order->orderable->email }}</a><br>
                                        <span class="text-body-secondary fs-6 info-naslov">
                                            Napomena:<br>
                                            {{ $order->orderable->note_to_seller }}
                                        </span>
                                    </p>
                                    <hr />
                                    <small class="text-body-secondary">Podaci firme</small>
                                    <p class="mb-0">
                                        <span class="text-body-secondary fs-6">Naziv kompanije: </span>{{ $order->orderable->company_name }}<br>
                                        <span class="text-body-secondary fs-6">Vlasnik kompanije: </span>{{ $order->orderable->company_owner }}<br>
                                        <span class="text-body-secondary fs-6">PIB: </span>{{ $order->orderable->company_pib }}<br>
                                        <span class="text-body-secondary fs-6">Bankovni račun: </span>{{ $order->orderable->bank_account_number }}<br>
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-4">
                                    <thead>
                                        <tr>
                                            <th>Artikl</th>
                                            <th>Cena</th>
                                            <th>Količina</th>
                                            <th style="text-align:right;">Ukupno</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPrice = 0;
                                        @endphp

                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ number_format($item->price,2,',','.') }}</td>
                                            <td>{{ $item->qty}}</td>
                                            <td style="text-align:right;">{{ number_format($item->price*$item->qty,2,',','.') }}</td>
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
                                            <td style="font-weight:normal;">Suma</td>
                                            <td style="text-align:right;">{{ number_format($totalPrice, 2, ',', '.') }} {{$store->global_currency}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:normal;">Dostava</td>
                                            <td style="text-align:right;">+ {{number_format($order->shipping, 2,',','.')}} {{$store->global_currency}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:normal;">Popust</td>
                                            <td style="text-align:right;">- 0.00</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:normal;">Ukupno za naplatu</td>
                                            <td style="text-align:right;">{{ number_format($order->grand_total,2, ',','.') }} {{$store->global_currency}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</html>

