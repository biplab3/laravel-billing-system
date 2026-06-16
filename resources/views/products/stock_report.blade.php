@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-box-seam"></i>
            Current Stock Report
        </h5>
    </div>

    <form method="GET" action="{{ route('stock.report') }}" class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        Product Name
                    </label>

                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Search Product">
                </div>

                <div class="col-md-8">

                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i>
                        Search
                    </button>

                    <a href="{{ route('stock.report') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                        Reset
                    </a>

                    <a href="{{ route('stock.report.export', request()->query()) }}" class="btn btn-success">
                        <i class="bi bi-download"></i>
                        Export CSV
                    </a>

                </div>

            </div>

        </div>
    </form>

    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">

                    <h6>Total Products</h6>

                    <h3>{{ $totalProducts }}</h3>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">

                    <h6>Total Stock Qty</h6>

                    <h3>{{ $totalStockQty }}</h3>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body text-center">

                    <h6>Inventory Value</h6>

                    <h3>
                        ₹ {{ number_format($inventoryValue, 2) }}
                    </h3>

                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                Stock Report
            </h5>
        </div>

        <div class="card-body p-0">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <!-- <th>SKU</th> -->
                        <th>Current Stock</th>
                        <th>Price</th>
                        <th>Stock Value</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($products as $product)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $product->name }}</td>

                            <!-- <td>{{ $product->sku }}</td> -->

                            <td>
                                <span class="badge bg-info">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>

                            <td>
                                ₹ {{ number_format($product->price, 2) }}
                            </td>

                            <td>
                                ₹ {{ number_format($product->stock_quantity * $product->price, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No Records Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

                <tfoot>

                    <tr class="table-warning fw-bold">

                        <td colspan="5" class="text-end">
                            Total Inventory Value
                        </td>

                        <td>
                            ₹ {{ number_format($inventoryValue, 2) }}
                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

@endsection