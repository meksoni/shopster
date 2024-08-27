@extends('admin.layouts.app', ['hideSidebar' => true, 'hideNavbar' => true, 'hideToolbar' => true, 'isLoginPage' => true])

@section('content')
<section class="position-relative overflow-hidden">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center ">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        <div class="col-md-6 col-lg-5 d-none d-md-block">
                            <img src="{{ asset('storage/bg/login-background.jpg')}}" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5">
                                <form action="{{ route('admin.authenticate')}}" method="POST" class="z-1 position-relative">
                                    @csrf

                                    <h5 class="fw-semibold mb-3 pb-3 text-md-start text-center" style="letter-spacing: 1px;">eCommerce Business Platform</h5>

                                    <div class="mb-4">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email')}}" />
                                        @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="password">Lozinka</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" />
                                        @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        @include('admin.components.authMessage')
                                    </div>

                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-primary w-100  btn-block" type="submit">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
