@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Add Purchase Entry</h5>

        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('purchases.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}"
                            class="form-control" required>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle" id="purchaseTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="30%">Product</th>
                                <th width="12%">UOM</th>
                                <th width="13%">Qty</th>
                                <th width="18%">Price</th>
                                <th width="18%">Amount</th>
                                <th width="9%">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-select product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} | Stock: {{ $product->stock_quantity }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="text" name="uom[]" class="form-control uom" placeholder="PCS" required>
                                </td>

                                <td>
                                    <input type="number" name="qty[]" class="form-control qty no-negative" min="0"
                                        step="0.01" value="1" required>
                                </td>

                                <td>
                                    <input type="number" name="price[]" class="form-control price no-negative" min="0"
                                        step="0.01" value="0" required>
                                </td>

                                <td>
                                    <input type="number" name="amount[]" class="form-control amount" value="0.00" readonly>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Grand Total</th>
                                <th>
                                    <input type="text" id="grandTotal" class="form-control fw-bold text-success"
                                        value="0.00" readonly>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="addRow" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Row
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Purchase
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const tableBody = document.querySelector('#purchaseTable tbody');
            const addRowBtn = document.getElementById('addRow');
            const grandTotalInput = document.getElementById('grandTotal');

            function calculateGrandTotal() {
                let total = 0;

                document.querySelectorAll('.amount').forEach(function (input) {
                    total += parseFloat(input.value) || 0;
                });

                grandTotalInput.value = total.toFixed(2);
            }

            function calculateRow(row) {
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const price = parseFloat(row.querySelector('.price').value) || 0;
                const amount = qty * price;

                row.querySelector('.amount').value = amount.toFixed(2);
                calculateGrandTotal();
            }

            function updateDeleteButtons() {
                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(function (row, index) {
                    const deleteBtn = row.querySelector('.remove-row');

                    if (!deleteBtn) return;

                    if (index === 0) {
                        deleteBtn.style.display = 'none';
                    } else {
                        deleteBtn.style.display = 'inline-block';
                    }
                });
            }

            addRowBtn.addEventListener('click', function () {
                const firstRow = tableBody.querySelector('tr');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelector('.product-select').value = '';
                newRow.querySelector('.uom').value = '';
                newRow.querySelector('.qty').value = 1;
                newRow.querySelector('.price').value = 0;
                newRow.querySelector('.amount').value = '0.00';

                tableBody.appendChild(newRow);

                updateDeleteButtons();
                calculateGrandTotal();
            });

            tableBody.addEventListener('keydown', function (e) {
                if (
                    e.target.classList.contains('no-negative') &&
                    (e.key === '-' || e.key === '+' || e.key === 'e' || e.key === 'E')
                ) {
                    e.preventDefault();
                }
            });

            tableBody.addEventListener('input', function (e) {
                if (e.target.classList.contains('no-negative')) {
                    if (parseFloat(e.target.value) < 0) {
                        e.target.value = 0;
                    }
                }

                if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
                    const row = e.target.closest('tr');
                    calculateRow(row);
                }
            });

            tableBody.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.remove-row');

                if (removeBtn) {
                    removeBtn.closest('tr').remove();

                    updateDeleteButtons();
                    calculateGrandTotal();
                }
            });

            tableBody.querySelectorAll('tr').forEach(function (row) {
                calculateRow(row);
            });

            updateDeleteButtons();

        });
    </script>
@endpush