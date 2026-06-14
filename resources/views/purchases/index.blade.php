@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Purchases</h5>

        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Purchase
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Purchase No</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th width="100">View</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration + ($purchases->currentPage() - 1) * $purchases->perPage() }}</td>
                            <td>PUR-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $purchase->supplier->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                            <td class="fw-bold text-success">
                                ₹ {{ number_format($purchase->total_amount, 2) }}
                            </td>
                            <td>
                                <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted p-4">
                                No purchase entries found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <div class="mt-3">
        {{ $purchases->links() }}
    </div>

@endsection