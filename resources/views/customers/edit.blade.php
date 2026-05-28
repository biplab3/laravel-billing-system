@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Customer</h4>

        <a href="/customers" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="/customers/{{ $customer->id }}/update">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $customer->mobile) }}">
                </div>

                <div class="mb-4">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control"
                        rows="3">{{ old('address', $customer->address) }}</textarea>
                </div>

                <div class="text-end">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Update Customer
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection