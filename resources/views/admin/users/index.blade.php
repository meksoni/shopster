@extends('admin.layouts.app')


@section('content')


<div class="content pb-0 pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid position-relative px-0">
        <div class="row">
            <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
                <h2 class="text-dark">Korisnici</h2>
                <!-- Dugme za otvaranje modal box-a za kreiranje korisnika -->
                <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    Dodaj novog korisnika
                </button>
            </div>

            <div class="col-12 mb-2">

                <div class="overflow-hidden card table-card">

                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="small text-uppercase bg-body text-body-secondary">
                                <tr>
                                    <th><h6>Ime</h6></th>
                                    <th><h6>Prezime</h6></th>
                                    <th><h6>Username</h6></th>
                                    <th><h6>Email</h6></th>
                                    <th><h6>Uloga/Dozvola</h6></th>
                                    <th><h6>Pozicija</h6></th>
                                    <th class="text-end fw-normal"><h6>Akcija</h6></th>
                                </tr>
                            </thead>
    
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="align-middle">
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ $user->position }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#{{$user->id}}">
                                                Izmeni
                                            </button>
                                            <a href="" class="btn btn-sm btn-danger ms-2" onclick="deleteUser({{ $user->id }})">
                                                Obriši korisnika
                                            </a>
                                        </td>
                                    </tr> 

                                    <div class="modal fade" id="{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                                @csrf
                                                
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Izmeni profil korisnika <span class="text-primary">'{{ $user->first_name }}'</span>
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="first_name" class="form-label">Ime</label>
                                                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="last_name" class="form-label">Prezime</label>
                                                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label for="username" class="form-label">Username</label>
                                                            <input type="text" class="form-control text-lowercase" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                                        </div>
                                                        <div class=" col-md-12 mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                        </div>
                                                        <div class=" col-md-12 mb-3">
                                                            <label for="role" class="form-label">Uloga/Dozvole</label>
                                                            <input type="number" class="form-control" id="role" name="role" value="{{ old('role', $user->role) }}" required>
                                                            <small>1 - Super Administrator | 2 - Admin</small>
                                                        </div>
                                                        <div class=" col-md-12 mb-3">
                                                            <label for="position" class="form-label">Pozicija</label>
                                                            <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $user->position) }}">
                                                        </div>
                                                    </div>
    
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Sačuvaj</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>

                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal za kreiranje korisnika -->
<div class="modal fade @if (count($errors) > 0) show @endif" id="createUserModal" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true" style="@if (count($errors) > 0) display:block; background:rgba(0,0,0,0.5); @endif">
    <div class="modal-dialog">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserLabel">Kreiraj novog korisnika</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_first_name" class="form-label">Ime</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="create_first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_last_name" class="form-label">Prezime</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="create_last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_username" class="form-label">Username</label>
                        <input type="text" class="form-control text-lowercase @error('username') is-invalid @enderror" id="create_username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="create_email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_password" class="form-label">Lozinka</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="create_password" name="password" required>
                        @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_password_confirmation" class="form-label">Potvrda lozinke</label>
                        <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_role" class="form-label">Uloga/Dozvole</label>
                        <input type="number" class="form-control @error('role') is-invalid @enderror" id="create_role" name="role" value="{{ old('role') }}" required>
                        @error('role')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small>1 - Super Administrator | 2 - Admin</small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_position" class="form-label">Pozicija</label>
                        <input type="text" class="form-control @error('position') is-invalid @enderror" id="create_position" name="position" value="{{ old('position') }}">
                        @error('position')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                    <button type="submit" class="btn btn-primary">Sačuvaj</button>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection

@section('customJS')
<script>

    function deleteUser(id) {
        var url = '{{ route("admin.users.delete", "ID") }}';
        var newUrl = url.replace("ID", id);

        if (confirm("Da li ste sigurni da želite da obrišete?")){
            $.ajax({
            url: newUrl,
            type: 'DELETE',
            data: {},
            dataType: 'json',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                if (response['status']) {
                    window.location.href='{{ route("admin.users")}}'
                }
            }
        })
        }
            
    }
</script>
@if (count($errors) > 0)
<script defer>

    document.addEventListener("DOMContentLoaded", function() {
        // Pronađi modal
        var createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));

        // Dodele event listener-a za zatvaranje modala
        var closeButton = document.querySelector('#createUserModal .btn-close');
        var closeModalButton = document.querySelector('#createUserModal .btn-secondary');
        
        closeButton.addEventListener('click', function () {
            createUserModal.hide();
        });
        
        closeModalButton.addEventListener('click', function () {
            createUserModal.hide();
        });
    });

</script>
@endif
@endsection