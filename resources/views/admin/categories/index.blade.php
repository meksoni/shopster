@extends('admin.layouts.app')

@section('title', 'AlfaSoft | Kategorije')

@section('content')
<div class="container-fluid px-4">
    @include('admin.components.messages')  
  </div>

<!-- Content -->
<div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
  <div class="container-fluid position-relative px-0">

      <div class="d-flex mb-4 align-items-center">
          <div class="flex-grow-1 border-top border-gray"></div>
          <h5 class="flex-shrink-0 mb-0 px-3">Lista kategorija</h5>
          <div class="flex-grow-1 border-top border-gray"></div>
      </div>

      <div class="row">
          <div class="col-12 mb-2">
              <div class="overflow-hidden card table-nowrap table-card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="mb-0">
                        <button onclick="window.location.href='{{ route('categories.index')}}'" type="reset" class="btn btn-secondary btn-sm">Refrešuj tabelu</button>
                    </div>
                  

                  <form action="" method="get" class="position-relative flex-grow-0 me-1 me-lg-2">
                    <!--Icon-->
                    <span class="material-symbols-rounded opacity-50 align-middle fs-5 position-absolute start-0 top-50 translate-middle-y ms-2">
                        search
                        </span>
                    <input type="text" value="{{ Request::get('keyword')}}" name="keyword" class="form-control ps-6 py-1" placeholder="Pretraži">
                </form>

                </div>
                <div class="positon-relative  card-refresh">
                <div class="table-responsive">
                  <table class="table mb-0">
                      <thead class="small text-uppercase bg-body text-body-secondary">
                          <tr>
                              <th><h6>ID</h6></th>
                              <th><h6>Naziv</h6></th>
                              <th><h6>Slug</h6></th>
                              <th><h6>Status</h6></th>
                              <th><h6>Datum kreiranja</h6></th>
                              <th class="text-end"><h6>Akcija</h6></th>
                          </tr>
                      </thead>

                      <tbody>
                      @if ($categories->isNotEmpty())
                          @foreach ($categories as $category)
                              <tr class="align-middle">
                                  <td>{{ $category->id }}</td>
                                  <td>{{ $category->name }}</td>
                                  <td>{{ $category->slug }}</td>
                                  <td>
                                      @if($category->status == 1)
                                      <span class="material-symbols-rounded align-middle text-success fs-2">
                                          check_circle
                                      </span>
                                      @else
                                      <span class="material-symbols-rounded align-middle text-danger fs-2">
                                          cancel
                                      </span>
                                      @endif
      
                                  </td>
                                  <td>{{ $category->created_at->format('Y-m-d ') }}</td>
                                  <td class="text-end">
                                      <div class="drodown">
                                          <a data-bs-toggle="dropdown" href="#" class="btn btn-outline-secondary p-1">
                                              <span class="material-symbols-rounded align-middle">
                                                  more_vert
                                              </span>
                                          </a>
                                          <div class="dropdown-menu dropdown-menu-end">
                                              <a href="{{ route('categories.edit', $category->id)}}" class="dropdown-item">Izmeni</a>
                                              <a href="" onclick="deleteCategory({{ $category->id }})" class="dropdown-item">Izbriši</a>
                                          </div>
                                      </div>
                                    </td>
                              </tr> 
                          @endforeach
                      @else
                          <tr>
                              <td colspan="5">Zapisi nisu pronadjeni</td>
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
                          {{ $categories->links( )}}
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
 
    function deleteCategory(id) {
        var url = '{{ route("categories.delete", "ID") }}';
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
                    window.location.href='{{ route("categories.index")}}'
                }
            }
        })
        }
            
    }

</script>
@endsection