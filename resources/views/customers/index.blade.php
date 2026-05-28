@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Customers</h4>

        <a href="/customers/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Customer
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td class="fw-semibold">{{ $c->name }}</td>
                            <td>{{ $c->mobile }}</td>
                            <td class="text-muted">{{ $c->address }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="/customers/{{ $c->id }}/edit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form method="POST" action="/customers/{{ $c->id }}/delete"
                                        onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted p-4">
                                No customers found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

@endsection