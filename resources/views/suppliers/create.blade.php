@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Add Supplier</h5>

        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('suppliers.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supplier Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Enter supplier name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mobile</label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control"
                            placeholder="Enter mobile number">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Enter email">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">GST No</label>
                        <input type="text" name="gst_no" value="{{ old('gst_no') }}" class="form-control"
                            placeholder="Enter GST number">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address" class="form-control" rows="3"
                            placeholder="Enter address">{{ old('address') }}</textarea>
                    </div>

                </div>

                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Supplier
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection