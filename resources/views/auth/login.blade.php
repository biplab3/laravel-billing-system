@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- LOGO / TITLE -->
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">BillingApp</h4>
                        <p class="text-muted">Login to your account</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                                autofocus>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <!-- REMEMBER -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input">
                            <label class="form-check-label">Remember Me</label>
                        </div>

                        <!-- BUTTON -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>

                        <!-- LINKS -->
                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <div class="text-center mt-2">
                            <span class="text-muted">Don't have an account?</span>
                            <a href="{{ route('register') }}">Register</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection