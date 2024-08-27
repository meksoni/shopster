@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
@include('admin.components.messages')  
</div>

<div class="content pb-0 pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid position-relative px-0">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="overflow-hidden card table-card">
                    <form action="{{ route('account.settings.update') }}" method="POST">
                    <div class="card-header">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Ime</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Prezime</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control text-lowercase" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Trenutna lozinka</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>

                            <div class="col-md-4">
                                <label for="password" class="form-label">Nova lozinka</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label">Potvrdite novu lozinku</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        
                        <button type="submit" class="btn btn-primary">Sačuvaj</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJS')
@endsection