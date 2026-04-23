@extends('partials.layouts.master_auth')

@section('title', 'Sign In | RideveConnect')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger" id="errorAlert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

<div>
    <img src="{{ asset('assets/images/auth/login_bg.jpg') }}" alt="Auth Background"
        class="auth-bg light w-full h-full opacity-60 position-absolute top-0">
    <img src="{{ asset('assets/images/auth/auth_bg_dark.jpg') }}" alt="Auth Background" class="auth-bg d-none dark">

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">
                        <img style="height:auto;width:200px;" src="{{ asset('assets/images/rideveconnect.png') }}"
                            alt="Logo" class="mb-4 mx-auto d-block">

                        <h2 class="mb-3 mb-8 fw-medium text-center">Login Page</h2>

                        <form class="row g-3" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row g-4">
                                <div class="col-12">
                                      <label for="login" class="col-md-4 col-form-label text-md-end">{{ __('Email or Phone') }}</label>

                                        <div class="col-md-12">
                                            <input id="login" 
                                                type="text" 
                                                class="form-control @error('login') is-invalid @enderror" 
                                                name="login" 
                                                value="{{ old('login') }}" 
                                                required 
                                                autofocus
                                                placeholder="Enter email or phone number">

                                            @error('login')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        placeholder="Enter your password"
                                        required
                                        autocomplete="current-password"
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <div class="form-text">
                                                <a href="{{ route('password.request') }}"
                                                   class="link link-primary text-muted text-decoration-underline">
                                                    Forgot password?
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-8">
                                    <button type="submit" class="btn btn-primary w-full mb-4">
                                        Sign In <i class="bi bi-box-arrow-in-right ms-1 fs-16"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <p class="position-relative text-center fs-12 mb-0">
                    © 2026 Rideve Media. Crafted with ❤️ by Rideve Media IT & Systems
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
