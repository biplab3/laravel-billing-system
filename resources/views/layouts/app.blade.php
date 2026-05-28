<!DOCTYPE html>
<html>

<head>
    <title>Billing System</title>

    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />

    <!-- ✅ Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-size: 14px;
        }

        .table {
            font-size: 14px;
        }

        .card-body {
            padding: 1rem !important;
        }
    </style>
</head>

<body class="bg-light">

    <!-- 🔥 NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">

            <a class="navbar-brand fw-bold" href="/dashboard">
                BillingApp
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="/products" class="nav-link">Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="/customers" class="nav-link">Customers</a>
                    </li>
                    <a href="/invoices" class="nav-link">Invoices</a>
                    <li class="nav-item">
                        <a href="/invoice/create" class="nav-link">New Invoice</a>
                    </li>
                    <a href="{{ route('sales.report') }}" class="btn btn-success btn-sm">
                        Sales Report
                    </a>
                    <a href="{{ route('customer.ledger') }}" class="nav-link">
                        Customer Ledger
                    </a>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item me-3 mt-2 text-white">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </li>
                        <li class="nav-item">
                            <form action="/logout" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="/login" class="btn btn-outline-light btn-sm me-2">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="/register" class="btn btn-primary btn-sm">Register</a>
                        </li>
                    @endauth
                </ul>

            </div>
        </div>
    </nav>

    <!-- 🔥 MAIN CONTENT -->
    <div class="container mt-4">

        <!-- ✅ SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- ✅ ERROR MESSAGE -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 🔥 PAGE CONTENT -->
        <div class="card shadow-sm">
            <div class="card-body">
                @yield('content')
            </div>
        </div>

    </div>

    <!-- ✅ JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

    @stack('scripts')

</body>

</html>