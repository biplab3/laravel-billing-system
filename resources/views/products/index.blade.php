@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Products</h4>

        <a href="/products/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>GST (%)</th>
                        <th>Stock</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>{{ $p->id }}</td>

                            <td class="fw-semibold text-dark">
                                {{ $p->name }}
                            </td>

                            <td class="text-success fw-bold">
                                ₹ {{ number_format($p->price, 2) }}
                            </td>

                            <td>{{ $p->gst_percent }}%</td>
                            <td>
                                {{ $p->stock_quantity }}

                                @if($p->stock_quantity < 5)
                                    <span class="badge bg-danger ms-2">Low</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <!-- EDIT -->
                                    <a href="/products/{{ $p->id }}/edit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- DELETE -->
                                    <form method="POST" action="/products/{{ $p->id }}/delete"
                                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted p-4">
                                No products found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

@endsection