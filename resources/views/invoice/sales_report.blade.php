@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            Sales Report
        </h4>

        <a href="{{ route('invoice.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Invoices
        </a>
    </div>

    <!-- FILTER CARD -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form method="GET" action="{{ route('sales.report') }}">

                <div class="row align-items-end g-3">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            From Date
                        </label>

                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            To Date
                        </label>

                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                    </div>

                    <div class="col-md-6">

                        <label class="form-label d-block">
                            &nbsp;
                        </label>

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                                Search
                            </button>

                            <a href="{{ route('sales.report') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                                Reset
                            </a>

                            {{-- enable after export route created --}}
                            {{--

                            <a href="{{ route('sales.report.export', request()->query()) }}" class="btn btn-success">
                                <i class="bi bi-download"></i>
                                Export CSV
                            </a>

                            --}}

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <!-- TOTAL CARD -->
    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm bg-success text-white">

                <div class="card-body">

                    <h6 class="mb-2">
                        Grand Sales Total
                    </h6>

                    <h3 class="fw-bold mb-0">
                        ₹ {{ number_format($grandTotal, 2) }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Invoices
                    </h6>

                    <h3 class="fw-bold mb-0">
                        {{ $invoices->count() }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <!-- REPORT TABLE -->
    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Sales Report
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-sm table-hover text-center mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Customer</th>
                            <th>Subtotal</th>
                            <th>GST</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($invoices as $invoice)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                                </td>

                                <td>
                                    {{ $invoice->customer->name ?? '-' }}
                                </td>

                                <td>
                                    ₹ {{ number_format($invoice->total_amount, 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format($invoice->gst_amount, 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format($invoice->discount_amount ?? 0, 2) }}
                                </td>

                                <td class="fw-bold text-success">
                                    ₹ {{ number_format($invoice->final_amount, 2) }}
                                </td>

                                <td>
                                    {{ $invoice->created_at->format('d M Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center text-muted p-4">

                                    No sales data found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr class="table-warning fw-bold">

                            <td colspan="6" class="text-end">

                                Grand Total

                            </td>

                            <td>
                                ₹ {{ number_format($grandTotal, 2) }}
                            </td>

                            <td></td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

@endsection