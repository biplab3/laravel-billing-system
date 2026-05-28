@extends('layouts.app')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Add Customer</h4>

        <a href="/customers" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('customers.store') }}">
                @csrf

                <!-- NAME -->
                <div class="mb-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter customer name"
                        value="{{ old('name') }}" required>
                </div>

                <!-- MOBILE -->
                <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number"
                        value="{{ old('mobile') }}">
                </div>

                <!-- ADDRESS -->
                <div class="mb-4">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3"
                        placeholder="Enter address">{{ old('address') }}</textarea>
                </div>

                <!-- BUTTON -->
                <div class="text-end">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Customer
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection