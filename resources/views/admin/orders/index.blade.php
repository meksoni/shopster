@extends('admin.layouts.app')



@section('content')
<div class="content pb-0 pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid position-relative px-0">
  
      <!--Widget row Title-->
      <div class="d-flex mb-4 align-items-center">
          <div class="flex-grow-1 border-top border-gray"></div>
          <h5 class="flex-shrink-0 mb-0 px-3">Lista porudžbina</h5>
          <div class="flex-grow-1 border-top border-gray"></div>
      </div>
  
  
      <div class="row">
        <div class="col-lg-12  mb-2 mx-auto">
            <div class="overflow-hidden card table-nowrap table-card">

                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="mb-0">
                      {{-- <button onclick="window.location.href='{{ route('categories.index')}}'" type="reset" class="btn btn-secondary btn-sm">Refresh Table</button> --}}
                      <a href="{{ route('orders.index')}}" class="btn-refresh">
                          <span class="material-symbols-rounded align-middle fs-5"
                            >refresh</span
                          >
                        </a>
                  </div>
                  
                    <div class="d-flex justify-content-between align-items-center">
                        <form action="" method="get" class="position-relative  flex-grow-0 me-1 me-lg-2">
                          
                
                          <span class="material-symbols-rounded opacity-50 align-middle fs-5 position-absolute start-0 top-50 translate-middle-y ms-2">
                              search
                          </span>
                          <input type="text" value="{{ Request::get('keyword')}}" name="keyword" class="form-control ps-6 py-1" placeholder="Pretraži porudžbine">
        
                        </form>

                    </div>

                </div>
                <div class="positon-relative  card-refresh">
                <div class="table-responsive">
                  <table class="table mb-0">
                      <thead class="small text-uppercase bg-body text-body-secondary">
                          <tr>
                              <th><h6>Porudžbina#</h6></th>
                              <th><h6>Status</h6></th>
                              <th><h6>Poručilac</h6></th>
                              <th><h6>Email</h6></th>
                              <th><h6>Telefon</h6></th>
                              <th><h6>Ukupna cena</h6></th>
                              <th><h6>Datum kupovine</h6></th>
                          </tr>
                      </thead>

                      <tbody>
                      @if ($orders->isNotEmpty())
                          @foreach ($orders as $order)
                              <tr class="align-middle">
                                
                                  <td>
                                      <a class="text-{{ $order->is_opened ? 'success' : 'primary' }}" href="{{ route('orders.detail',[$order->id])}}">{{ $order->id }}
                                          <span class="material-symbols-rounded align-middle fs-6">
                                              visibility
                                          </span>
                                      </a>
                                  </td>
                                  <td>
                                    @if($order->status == 'na_cekanju')
                                        <span class="badge bg-warning d-flex align-items-center" data-tippy-content="Status 'Na čekanju' označava da je porudžbina primljena, ali još nije obrađena ili poslata." data-tippy-placement="right">
                                            <span class="material-symbols-rounded">hourglass_empty</span> <span class="ms-1">Na čekanju</span>
                                        </span>
                                    @elseif($order->status == 'poslato')
                                        <span class="badge bg-info d-flex align-items-center" data-tippy-content="Status 'Poslato' označava da je porudžbina napustila skladište i da je u procesu dostave." data-tippy-placement="right">
                                            <span class="material-symbols-rounded">local_shipping</span> <span class="ms-1">Poslato</span>
                                        </span>
                                    @elseif($order->status == 'isporuceno')
                                        <span class="badge bg-success d-flex align-items-center" data-tippy-content="Status 'Isporučeno' označava da je porudžbina uspešno isporučena i naplaćena kupcu." data-tippy-placement="right">
                                            <span class="material-symbols-rounded">check_circle</span> <span class="ms-1">Isporučeno</span>
                                        </span>
                                    @elseif($order->status == 'otkazano')
                                        <span class="badge bg-danger d-flex align-items-center" data-tippy-content="Status 'Otkazano' označava da je porudžbina otkazana." data-tippy-placement="right">
                                            <span class="material-symbols-rounded">cancel</span> <span class="ms-1">Otkazano</span>
                                        </span>
                                    @elseif($order->status == 'vraceno')
                                        <span class="badge bg-secondary d-flex align-items-center" data-tippy-content="Status 'Vraćeno' označava da je kupac vratio porudžbinu." data-tippy-placement="right">
                                            <span class="material-symbols-rounded">undo</span> <span class="ms-1">Vraćeno</span>
                                        </span>
                                    @elseif($order->status == 'neuspesno')
                                        <span class="badge bg-dark d-flex align-items-center" data-tippy-content="Status 'Neuspešno' označava da je došlo do problema prilikom obrade porudžbine." data-tippy-placement="right">
                                            <span class="material-symbols-rounded">warning</span> <span class="ms-1">Neuspešno</span>
                                        </span>
                                    @endif
                                </td>
                                  <td>{{ $order->orderable->full_name}}</td>
                                  <td>{{ $order->orderable->email }}</td>
                                  <td>{{ $order->orderable->phone_number }}</td>
                                  <td>{{ number_format($order->grand_total,2) }} {{ $store->global_currency }}</td>
                                  
                                  <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y - H:i:s') }}</td>
                                
                              </tr> 
                          @endforeach
                      @else
                          <tr>
                              <td colspan="5">Nema porudžbina</td>
                          </tr>
                      @endif  
                      </tbody>

                  </table>
                </div>
              </div>

              <div class="content-right-footer card-footer">
                  <!--Footer for emails-->
                  <div class="d-flex justify-content-between align-items-center">
                      <div>
                          {{-- <div class="progress mb-1" style="height: 4px;">
                              <div class="progress-bar bg-success" aria-valuemax="100"
                                  aria-valuenow="20" style="width: 20%;">

                              </div>
                          </div>
                          <small>4 GB <span class="d-none d-sm-inline-block">(20%) </span> of 20 GB
                              used</small> --}}
                      </div>
                      <div>
                          {{ $orders->links( )}}
                      </div>
                  </div>
              </div>
          
        </div>
      </div>
      </div>
  
    </div>
</div>

@endsection

@section('customJS')
<script>
   
</script>
@endsection