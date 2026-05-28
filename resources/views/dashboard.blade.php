@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Dashboard</h5>

        <a href="{{ route('invoice.index') }}" class="btn btn-primary">
            <i class="bi bi-receipt"></i> View Invoices
        </a>
    </div>

    <form method="GET" action="{{ route('dashboard') }}" class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end g-3">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label d-block">&nbsp;</label>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>

                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <div class="row g-3 mb-4">

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="small">
                            {{ request('from_date') || request('to_date') ? 'Period Sales' : 'Today Sales' }}
                        </div>

                        <h5 class="fw-bold mb-0">
                            ₹ {{ number_format($periodSales, 2) }}
                        </h5>
                    </div>

                    <i class="bi bi-currency-rupee fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="small">
                            {{ request('from_date') || request('to_date') ? 'Period Sales' : 'Today Sales' }}
                        </div>

                        <h5 class="fw-bold mb-0">
                            ₹ {{ number_format($periodSales, 2) }}
                        </h5>
                    </div>

                    <i class="bi bi-graph-up-arrow fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-dark text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="small">Invoices</div>
                        <h5 class="fw-bold mb-0">
                            {{ $totalInvoices }}
                        </h5>
                    </div>

                    <i class="bi bi-receipt fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="small">Customers</div>
                        <h5 class="fw-bold mb-0">
                            {{ $totalCustomers }}
                        </h5>
                    </div>

                    <i class="bi bi-people-fill fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-secondary text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="small">Products</div>
                        <h5 class="fw-bold mb-0">
                            {{ $totalProducts }}
                        </h5>
                    </div>

                    <i class="bi bi-box-seam fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-warning h-100">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="small">Low Stock</div>
                        <h5 class="fw-bold mb-0">
                            {{ $lowStockProducts }}
                        </h5>
                    </div>

                    <i class="bi bi-exclamation-triangle-fill fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        {{ request('from_date') || request('to_date') ? 'Filtered Sales Chart' : 'Last 7 Days Sales' }}
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Business Summary</h5>
                </div>
                <div class="card-body">
                    <canvas id="summaryChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Latest Invoices</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>View</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($latestInvoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $invoice->customer->name ?? '-' }}</td>
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
                            <td colspan="6" class="text-muted p-4">
                                No invoices found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const salesLabels = @json($salesChartLabels);
        const salesData = @json($salesChartData);

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales',
                    data: salesData,
                    borderWidth: 3,
                    tension: 0.4
                }]
            }
        });

        new Chart(document.getElementById('summaryChart'), {
            type: 'doughnut',
            data: {
                labels: ['Invoices', 'Customers', 'Products', 'Low Stock'],
                datasets: [{
                    data: [
                                                            {{ $totalInvoices }},
                                                            {{ $totalCustomers }},
                                                            {{ $totalProducts }},
                        {{ $lowStockProducts }}
                    ]
                }]
            }
        });
    </script>

@endsection