@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Edit User</h4>
    </div>
<div class="card-body">

    @php
        $selectedPermissions = $user->permissions ?? [];
    @endphp

    <form action="{{ route('users.update',$user->id) }}"
          method="POST">

        @csrf

        <div class="mb-3">
            <label>Name</label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ $user->name }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Email</label>

            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ $user->email }}"
                   required>
        </div>

        <hr>

        <h5>Permissions</h5>

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

        <div class="row">

            @foreach($modules as $module)

                <div class="col-md-3 mb-2">

                    <div class="form-check">

                        <input class="form-check-input"
                               type="checkbox"
                               name="permissions[]"
                               value="{{ $module }}"
                               {{ in_array($module,$selectedPermissions) ? 'checked' : '' }}>

                        <label class="form-check-label">
                            {{ $module }}
                        </label>

                    </div>

                </div>

            @endforeach

        </div>

        <button class="btn btn-success mt-3">
            Update User
        </button>

    </form>

</div>

</div>

@endsection
