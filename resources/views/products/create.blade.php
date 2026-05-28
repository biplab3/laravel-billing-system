@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Add Product</h4>

        <a href="/products" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('products.store') }}">
                @csrf

                <!-- PRODUCT NAME -->
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter product name"
                        value="{{ old('name') }}" required>
                </div>

                <!-- PRICE -->
                <div class="mb-3">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price"
                        value="{{ old('price') }}" required>
                </div>

                <!-- GST -->
                <div class="mb-4">
                    <label class="form-label">GST (%)</label>
                    <input type="number" name="gst_percent" class="form-control" placeholder="Enter GST %"
                        value="{{ old('gst_percent', 0) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="form-control" value="0">
                </div>

                <!-- BUTTON -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Product
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection