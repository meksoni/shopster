@extends('admin.layouts.app')

@section('title', 'AlfaSoft | Podešavanje prodavnice')

@section('content')
<div class="container-fluid px-4">
@include('admin.components.messages')  
</div>

<div class="content pb-0 pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid position-relative px-0">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="overflow-hidden card table-card">
                    <form action="{{ route('store.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-header  ">
                            @csrf
                            
                            <div class="row d-flex align-items-center">
                                <div class="col-md-6 mb-3">
                                    <label for="cp_name" class="form-label">Naziv Firme/Prodavnice</label>
                                    <input type="text" class="form-control" id="cp_name" name="cp_name" value="{{ old('cp_name', $store->cp_name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cp_logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control" id="cp_logo" name="cp_logo">
                                </div>
                            </div>

                            <div class="row d-flex align-items-center">
                                <div class="col-md-6 mb-3">
                                    <label for="cp_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="cp_email" name="cp_email" value="{{ old('cp_mail', $store->cp_email) }}">
                                </div>
                                <div class="col-md-6 mb-3">

                                    <label for="cp_phone" class="form-label">Telefon</label>
                                    <input type="text" class="form-control" id="cp_phone" data-inputmask="'mask': '+381 99 999[9999]'" name="cp_phone" value="{{ old('cp_phone', $store->cp_phone) }}">                                </div>
                            </div>

                            <div class="row d-flex align-items-center">
                                <div class="col-md-4 mb-3">
                                    <label for="cp_bank_account" class="form-label">Broj Bankovnog Računa</label>
                                    <input type="text" class="form-control" data-inputmask="'mask': '999 [9999999999999] 99'" id="cp_bank_account" name="cp_bank_account" value="{{ old('cp_bank_account', $store->cp_bank_account) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cp_pib" class="form-label">PIB Firme</label>
                                    <input type="text" class="form-control" id="cp_pib" name="cp_pib" value="{{ old('cp_pib', $store->cp_pib) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cp_mb" class="form-label">Matični Broj Firme</label>
                                    <input type="text" class="form-control" id="cp_mb" name="cp_mb" value="{{ old('cp_mb', $store->cp_mb) }}">
                                </div>
                                
                            </div>
                            
                            <div class="row d-flex align-items-center">
                                <div class="col-md-6 mb-3">
                                    <label for="global_currency" class="form-label">Globalna Valuta <small class="fw-semibold">*prikazuje se na prodavnici</small></label>
                                    <input type="text" class="form-control" id="global_currency" name="global_currency" value="{{ old('global_currency', $store->global_currency) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cp_address" class="form-label">Adresa</label>
                                    <input type="text" class="form-control" id="cp_address" name="cp_address" value="{{ old('cp_address', $store->cp_address) }}">
                                </div>
                            </div>

                            <div class="row d-flex align-items-center">
                                <div class="col-md-4 mb-3">
                                    <label for="cp_city" class="form-label">Grad</label>
                                    <input type="text" class="form-control" id="cp_city" name="cp_city" value="{{ old('cp_city', $store->cp_city) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cp_country" class="form-label">Država</label>
                                    <input type="text" class="form-control" id="cp_country" name="cp_country" value="{{ old('cp_country', $store->cp_country) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cp_zip" class="form-label">Poštanski Broj</label>
                                    <input type="text" class="form-control" id="cp_zip" name="cp_zip" value="{{ old('cp_zip', $store->cp_zip) }}">
                                </div>
                            </div>

                            <div class="row d-flex align-items-center">
                                <div class="col-md-4 mb-3">
                                    <label for="twitter_url" class="form-label">Twitter Link</label>
                                    <div class="input-group">
                                        <span id="twitter_url" class="input-group-text">https://</span>
                                        <input type="url" class="form-control" aria-describedby="twitter_url" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $store->twitter_url) }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="facebook_url" class="form-label">Facebook Link</label>
                                    <div class="input-group">
                                    <span id="facebook_url" class="input-group-text">https://</span>
                                    <input type="url" class="form-control" aria-describedby="facebook_url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $store->facebook_url) }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="instagram_url" class="form-label">Instagram Link</label>
                                    <div class="input-group">
                                    <span id="instagram_url" class="input-group-text">https://</span>
                                    <input type="url" class="form-control" aria-describedby="instagram_url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $store->instagram_url) }}">
                                    </div>
                                </div>
                            </div>
                           

                            <button type="submit" class="btn btn-primary">Sačuvajte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

