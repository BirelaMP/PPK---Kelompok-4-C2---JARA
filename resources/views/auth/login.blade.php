@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<h5 class="fw-bold text-center mb-3">Sign In to Your Workspace</h5>

<form method="POST" action="{{ route('login') }}" class="mb-4">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', 'admin@jara.com') }}" required autofocus placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" value="password123" required placeholder="Enter password">
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label small text-secondary" for="remember">Remember me on this computer</label>
    </div>

    <button type="submit" class="btn btn-jara w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
    </button>
</form>

<!-- Demo credentials accordion / quick fill -->
<div class="p-3 bg-light rounded-3 mb-3 border small">
    <div class="fw-bold text-muted mb-2"><i class="bi bi-info-circle me-1"></i> Demo Accounts (One-Click Fill):</div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm btn-outline-danger w-50" onclick="fillDemo('admin@jara.com', 'password')">
            <i class="bi bi-shield-lock me-1"></i> Admin
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary w-50" onclick="fillDemo('user@jara.com', 'password')">
            <i class="bi bi-person me-1"></i> User
        </button>
    </div>
</div>

<div class="text-center small text-muted">
    Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Create Account</a>
</div>

<script>
    function fillDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
