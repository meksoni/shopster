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
                        <form action="{{ route('problems.submit') }}" method="POST" enctype="multipart/form-data">
                        <div class="card-header  ">
                                @csrf
                                
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Naziv Firme</label>
                                        <input type="text" class="form-control" disabled  value="{{ $store->cp_name }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kontakt telefon</label>
                                        <input type="text" class="form-control"  value="{{ $store->cp_phone }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kontakt email</label>
                                        <input type="text" class="form-control"  value="{{ $store->cp_email }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="description" class="form-label">Opis problema</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" cols="30" rows="5" required></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
    
                                <div class="d-flex align-items-start">
                                    <button type="submit" class="btn btn-primary me-4">Prijavite problem</button>

                                    <p class="text-muted small">
                                        Prijavu problema možete izvršiti i pozivom na <a href="tel:+381628438016">+381 62 843 80 16</a><br> 
                                        svakim radnim danom od 10h - 18h.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection