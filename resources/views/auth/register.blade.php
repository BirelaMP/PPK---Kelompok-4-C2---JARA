@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<h5 class="fw-bold text-center mb-3">Create New Account</h5>

<form method="POST" action="{{ route('register') }}" class="mb-4">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label small fw-semibold text-secondary">Full Name</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
            <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
        </div>
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimum 8 characters">
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
            <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter password">
        </div>
    </div>

    <button type="submit" class="btn btn-jara w-100">
        <i class="bi bi-person-plus me-1"></i> Register Account
    </button>
</form>

<div class="text-center small text-muted">
    Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign In</a>
</div>
@endsection
