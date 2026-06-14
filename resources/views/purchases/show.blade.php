@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">
            Purchase Details
        </h5>

        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                Purchase No: PUR-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}
            </h5>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    <strong>Supplier:</strong>
                    <div>{{ $purchase->supplier->name ?? '-' }}</div>
                </div>

                <div class="col-md-4">
                    <strong>Purchase Date:</strong>
                    <div>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</div>
                </div>

                <div class="col-md-4">
                    <strong>Total Amount:</strong>
                    <div class="fw-bold text-success">
                        ₹ {{ number_format($purchase->total_amount, 2) }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Purchase Items</h5>
        </div>

        <div class="card-body p-0">

            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($purchase->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>₹ {{ number_format($item->price, 2) }}</td>
                            <td class="fw-bold text-success">
                                ₹ {{ number_format($item->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="table-warning fw-bold">
                        <td colspan="4" class="text-end">Grand Total</td>
                        <td>₹ {{ number_format($purchase->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

@endsection