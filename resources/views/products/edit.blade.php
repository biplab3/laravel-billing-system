@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Product</h4>

        <a href="/products" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="/products/{{ $product->id }}/update">
                @csrf

                <!-- PRODUCT NAME -->
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>

                <!-- PRICE -->
                <div class="mb-3">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" step="0.01" name="price" class="form-control"
                        value="{{ old('price', $product->price) }}" required>
                </div>

                <!-- GST -->
                <div class="mb-4">
                    <label class="form-label">GST (%)</label>
                    <input type="number" name="gst_percent" class="form-control"
                        value="{{ old('gst_percent', $product->gst_percent) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}">
                </div>

                <!-- BUTTON -->
                <div class="text-end">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Update Product
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection