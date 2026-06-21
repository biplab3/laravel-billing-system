@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Add User</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <hr>

                <h5>Permissions</h5>

                <div class="row">

                    @php
                        $modules = [
                            'Dashboard',
                            'Products',
                            'Customers',
                            'Suppliers',
                            'Invoices',
                            'Sales Report',
                            'Customer Ledger',
                            'Purchases',
                            'Purchase Report',
                            'Stock Report',
                            'Users'
                        ];
                    @endphp

                    @foreach($modules as $module)

                        <div class="col-md-3 mb-2">

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $module }}">

                                <label class="form-check-label">
                                    {{ $module }}
                                </label>

                            </div>

                        </div>

                    @endforeach

                </div>

                <button class="btn btn-primary mt-3">
                    Save User
                </button>

            </form>

        </div>

    </div>

@endsection