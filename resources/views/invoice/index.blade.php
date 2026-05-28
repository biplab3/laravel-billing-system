@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Invoices</h4>

        <a href="/invoice/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Invoice
        </a>
    </div>
    <form method="GET" action="{{ route('invoice.index') }}" class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row align-items-end g-2">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        Invoice No
                    </label>

                    <input type="text" name="invoice_no" value="{{ request('invoice_no') }}" class="form-control"
                        placeholder="INV-00001">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Customer
                    </label>

                    <input type="text" name="customer" value="{{ request('customer') }}" class="form-control"
                        placeholder="Customer name">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        From Date
                    </label>

                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        To Date
                    </label>

                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label d-block">&nbsp;</label>

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search"></i>

                        </button>

                        <a href="{{ route('invoice.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise"></i>
                            Reset
                        </a>

                        <a href="{{ route('invoice.export.csv', request()->query()) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i>

                        </a>

                    </div>
                </div>

            </div>

        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover text-center mb-0">
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
                        <th width="240">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                            <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $invoice->customer->name ?? '-' }}</td>
                            <td>₹ {{ number_format($invoice->total_amount, 2) }}</td>
                            <td>₹ {{ number_format($invoice->gst_amount, 2) }}</td>
                            <td>₹ {{ number_format($invoice->discount_amount ?? 0, 2) }}</td>
                            <td class="fw-bold text-success">₹ {{ number_format($invoice->final_amount, 2) }}</td>
                            <td>{{ $invoice->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="{{ route('invoice.receipt', $invoice->id) }}" target="_blank"
                                        class="btn btn-dark btn-sm">
                                        <i class="bi bi-receipt"></i>
                                    </a>

                                    <form method="POST" action="{{ route('invoice.delete', $invoice->id) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted p-4">
                                No invoices found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
    </div>

@endsection