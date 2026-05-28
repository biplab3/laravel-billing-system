@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            Customer Ledger Report
        </h4>

        <a href="{{ route('invoice.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Invoices
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form method="GET" action="{{ route('customer.ledger') }}">

                <div class="row align-items-end g-3">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Customer</label>

                        <select name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
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

                    <div class="col-md-4">
                        <label class="form-label d-block">&nbsp;</label>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                                Search
                            </button>

                            <a href="{{ route('customer.ledger') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                                Reset
                            </a>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>

    @if($selectedCustomer)

        <div class="row mb-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h6 class="mb-2">Customer</h6>
                        <h4 class="fw-bold mb-0">
                            {{ $selectedCustomer->name }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h6 class="mb-2">Total Sales</h6>
                        <h4 class="fw-bold mb-0">
                            ₹ {{ number_format($totalSales, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Invoices</h6>
                        <h4 class="fw-bold mb-0">
                            {{ $invoices->count() }}
                        </h4>
                    </div>
                </div>
            </div>

        </div>

    @endif

    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Customer Invoice History</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Subtotal</th>
                            <th>GST</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>View</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($invoices as $invoice)

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                                </td>

                                <td>₹ {{ number_format($invoice->total_amount, 2) }}</td>

                                <td>₹ {{ number_format($invoice->gst_amount, 2) }}</td>

                                <td>₹ {{ number_format($invoice->discount_amount ?? 0, 2) }}</td>

                                <td class="fw-bold text-success">
                                    ₹ {{ number_format($invoice->final_amount, 2) }}
                                </td>

                                <td>{{ $invoice->created_at->format('d M Y') }}</td>

                                <td>
                                    <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-muted p-4">
                                    @if(request('customer_id'))
                                        No invoices found for this customer
                                    @else
                                        Please select a customer to view ledger
                                    @endif
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    @if($selectedCustomer)
                        <tfoot>
                            <tr class="table-warning fw-bold">
                                <td colspan="5" class="text-end">
                                    Ledger Total
                                </td>

                                <td>
                                    ₹ {{ number_format($totalSales, 2) }}
                                </td>

                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif

                </table>

            </div>

        </div>

    </div>

@endsection