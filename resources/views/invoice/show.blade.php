@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h4 class="fw-bold mb-0">Invoice Preview</h4>

        <div>
            <a href="/invoice/create" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <button onclick="window.print()" class="btn btn-success btn-sm">
                <i class="bi bi-printer"></i> Print
            </button>

            <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-download"></i> Download PDF
            </a>
            <a href="{{ route('invoice.receipt', $invoice->id) }}" target="_blank" class="btn btn-dark btn-sm">
                <i class="bi bi-receipt"></i> Thermal Print
            </a>
        </div>
    </div>

    <div class="card shadow-sm" id="invoiceArea">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h3 class="fw-bold mb-0">BillingApp</h3>
                <small class="text-muted">Invoice / Sales Bill</small>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold">Bill To</h6>
                    <p class="mb-1">{{ $invoice->customer->name }}</p>
                    <p class="mb-1">{{ $invoice->customer->mobile ?? '' }}</p>
                    <p class="mb-0 text-muted">{{ $invoice->customer->address ?? '' }}</p>
                </div>

                <div class="col-md-6 text-md-end">
                    <h6 class="fw-bold">Invoice Details</h6>
                    <p class="mb-1"><strong>Invoice No:</strong> INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="mb-0"><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th class="text-start">Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($invoice->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-start">{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹ {{ number_format($item->price, 2) }}</td>
                            <td>₹ {{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row justify-content-end">
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tr>
                            <th>Subtotal</th>
                            <td class="text-end">
                                ₹ {{ number_format($invoice->total_amount, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <th>GST</th>
                            <td class="text-end">
                                ₹ {{ number_format($invoice->gst_amount, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <th>Discount</th>
                            <td class="text-end">
                                ₹ {{ number_format($invoice->discount_amount ?? 0, 2) }}
                            </td>
                        </tr>

                        <tr class="table-success">
                            <th>Grand Total</th>
                            <td class="text-end fw-bold text-success">
                                ₹ {{ number_format($invoice->final_amount, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="mb-1 fw-semibold">Thank you for your business!</p>
                <small class="text-muted">This is a computer-generated invoice.</small>
            </div>

        </div>
    </div>

    <style>
        @media print {
            body {
                background: #fff !important;
            }

            nav,
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .container {
                max-width: 100% !important;
            }
        }
    </style>

@endsection