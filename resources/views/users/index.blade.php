@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Users</h5>

        ```
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add User
        </a>
        ```

    </div>

    <div class="card shadow-sm">

        ```
        <div class="card-body p-0">

            <table class="table table-bordered table-hover text-center mb-0">

                <thead class="table-dark">

                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="160">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <!-- <td>

                                                                        @if($user->role == 'Admin')
                                                                            <span class="badge bg-danger">
                                                                                Admin
                                                                            </span>
                                                                        @elseif($user->role == 'Manager')
                                                                            <span class="badge bg-primary">
                                                                                Manager
                                                                            </span>
                                                                        @else
                                                                            <span class="badge bg-success">
                                                                                Sales
                                                                            </span>
                                                                        @endif

                                                                    </td> -->

                            <td>

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form method="POST" action="{{ route('users.delete', $user->id) }}"
                                        onsubmit="return confirm('Are you sure?')">

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

                            <td colspan="5" class="text-muted p-4">

                                No Users Found

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
        ```

    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>

@endsection