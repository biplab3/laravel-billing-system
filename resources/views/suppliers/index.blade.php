@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Suppliers</h5>

        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Supplier
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Supplier Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>GST No</th>
                        <th>Address</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->mobile ?? '-' }}</td>
                            <td>{{ $supplier->email ?? '-' }}</td>
                            <td>{{ $supplier->gst_no ?? '-' }}</td>
                            <td>{{ $supplier->address ?? '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form method="POST" action="{{ route('suppliers.delete', $supplier->id) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this supplier?')">
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
                            <td colspan="7" class="text-muted p-4">
                                No suppliers found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <div class="mt-3">
        {{ $suppliers->links() }}
    </div>

@endsection