@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="text-center mb-4">
                        <h4 class="fw-bold">BillingApp</h4>
                        <p class="text-muted">Create your account</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                                autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-person-plus"></i> Register
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted">Already registered?</span>
                            <a href="{{ route('login') }}">Login</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection