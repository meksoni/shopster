@extends('admin.layouts.app')



@section('content')

<div class="container-fluid px-4">
  @include('admin.components.messages')  
</div>


<div class="content pb-0 pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
  <div class="container-fluid position-relative px-0">

    <!--Widget row Title-->
    <div class="d-flex mb-4 align-items-center">
        <div class="flex-grow-1 border-top border-gray"></div>
        <h5 class="flex-shrink-0 mb-0 px-3">Lista proizvoda</h5>
        <div class="flex-grow-1 border-top border-gray"></div>
    </div>


    <div class="row">
      <div class="col-lg-12  mb-2 mx-auto">
        <!--card-->
        <div class="card table-card table-nowrap overflow-hidden h-100">
          <div class="d-flex card-header align-items-center justify-content-between">
  
              <a href="{{ route('products.index') }}" class="btn-refresh">
                <span class="material-symbols-rounded  fs-4">refresh</span>
              </a>
  
  
              <form action="" method="get" class="position-relative flex-grow-0 me-1 me-lg-2 mt-3">
                <!--Icon-->
                <span class="material-symbols-rounded opacity-50 align-middle fs-4 position-absolute start-0 top-50 translate-middle-y ms-2">
                    search
                    </span>
                <input type="text" value="{{ Request::get('keyword')}}" name="keyword" class="form-control ps-6 py-1"
                    placeholder="Pretraži (naziv,šifra...)">
              </form>
  
          </div>
          <div class="position-relative card-refresh">
            <div class="table-responsive">
              <table
                class="table table-striped mb-0"
                style="width: 100%">
                <thead class="small text-uppercase bg-body text-body-secondary">
                  <tr>
                    <th><h6>ID</h6></th>
                    <th><h6>Proizvod</h6></th>
                    <th><h6>Cena</h6></th>
                    <th><h6>Količina</h6></th>
                    <th><h6>Šifra</h6></th>
                    <th><h6>Status</h6></th>
                    <th class="text-end"><h6>Akcija</h6></th>
                  </tr>
                </thead>
                <tbody class="align-middle">
                @if ($products->isNotEmpty())
                  
                  @foreach ($products as $product)
  
                  @php
                    $productImage = $product->product_images->first();
                  @endphp
                    <tr class="align-middle">
                        <td>{{ $product->id }}</td>
                        <td>
                          <div class="d-flex align-items-center">
                            @if (!empty($productImage->image))
                            <img src="{{ asset('uploads/product/small/'.$productImage->image)}}" class="avatar sm rounded-pill me-3 flex-shrink-0">
                            @else
                            <img src="{{asset('storage/products/box.png') }}" class="avatar sm rounded me-3 flex-shrink-0"/>
                            @endif
                            <div>
                              <div class=" mb-0 lh-1"> {{ $product->title }}</div>
                            </div>
                          </div>
                        </td>
                        <td>{{ number_format($product->price,0,',','.') }} <small>{{$store->global_currency}}</small></td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->sku }}</td>
    
                        <td>
                            @if($product->status == 1)
                            <span class="material-symbols-rounded align-middle text-success fs-2">
                                check_circle
                            </span>
                            @else
                            <span class="material-symbols-rounded align-middle text-danger fs-2">
                                cancel
                            </span>
                            @endif
    
                        </td>
                        <td class="text-end">
                            <div class="drodown">
                                <a data-bs-toggle="dropdown" href="#" class="btn btn-outline-secondary p-1">
                                    <span class="material-symbols-rounded align-middle">
                                        more_vert
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('products.edit', $product->id)}}" class="dropdown-item">Izmeni</a>
                                    <a href='' onclick="deleteProduct({{ $product->id }})" class="dropdown-item">Obriši</a>
                                </div>
                            </div>
                          </td>
                    </tr> 
                  @endforeach
              @else
                    <tr>
                      <td>Records Not Found</td>
                    </tr>
              @endif
                </tbody>
              </table>
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
                      {{ $products->links()}}
                  </div>
              </div>
          </div>
  
            <!--:Loader:-->
            <div
                class="loader-refresh w-100 h-100 
                position-absolute start-0 top-0 bottom-0 end-0 d-flex 
                align-items-center justify-content-center bg-dark bg-opacity-25">
              <div>
                <div
                  class="spinner-border spinner-border-sm me-2 text-white"
                  role="status"
                ></div>
                <span class="text-white">Refreshing...</span>
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

  
  function deleteProduct(id) {
        var url = '{{ route("products.delete", "ID") }}';
        var newUrl = url.replace("ID", id);

        if (confirm("Are you sure you want to delete?")){
            $.ajax({
            url: newUrl,
            type: 'delete',
            data: {},
            dataType: 'json',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                if (response['status']) {
                    window.location.href='{{ route("products.index")}}'
                }
            }
        })
        }
            
    }


</script>
@endsection