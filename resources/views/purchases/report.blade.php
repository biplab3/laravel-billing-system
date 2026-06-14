@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Purchase Report</h5>

        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Purchases
        </a>
    </div>

    <form method="GET" action="{{ route('purchase.report') }}" class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end g-3">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>

                <div class="col-md-5">
                    <label class="form-label d-block">&nbsp;</label>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>

                        <a href="{{ route('purchase.report') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>

                        <a href="{{ route('purchase.report.export', request()->query()) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Export CSV
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="small">Total Purchase Amount</div>
                    <h4 class="fw-bold mb-0">
                        ₹ {{ number_format($totalPurchaseAmount, 2) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="small">Total Purchase Entries</div>
                    <h4 class="fw-bold mb-0">
                        {{ $totalPurchaseEntries }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Purchase Report List</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Purchase No</th>
                        <th>Supplier</th>
                        <th>Purchase Date</th>
                        <th>Total Amount</th>
                        <th width="100">View</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
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
                                No purchase records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr class="table-warning fw-bold">
                        <td colspan="4" class="text-end">Grand Total</td>
                        <td>
                            ₹ {{ number_format($totalPurchaseAmount, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection