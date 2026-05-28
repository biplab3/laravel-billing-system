@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Create Invoice</h4>

        <a href="/dashboard" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('invoice.store') }}">
        @csrf

        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-md-8">

                <div class="card shadow-sm mb-3">
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Customer</label>
                            <select name="customer_id" class="form-select customer-select" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center" id="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 35%;">Product</th>
                                        <th style="width: 12%;">Qty</th>
                                        <th style="width: 18%;">Price</th>
                                        <th style="width: 18%;">Total</th>
                                        <th style="width: 17%;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="product_id[]" class="form-select product-select" required>
                                                <option value="">-- Select Product --</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}"
                                                        data-gst="{{ $p->gst_percent }}">
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" name="qty[]" class="form-control text-center" value="1"
                                                min="1" onkeyup="calc(this)" onchange="calc(this)">
                                        </td>

                                        <td>
                                            <input type="text" name="price[]" class="form-control text-end" value="0"
                                                readonly>
                                        </td>

                                        <td>
                                            <input type="text" name="total[]" class="form-control text-end" value="0"
                                                readonly>
                                        </td>
                                        <input type="hidden" name="gst_percent[]" value="0">
                                        <input type="hidden" name="gst_amount[]" value="0">

                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-success btn-sm" onclick="addRow()">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeRow(this)">
                                                    <i class="bi bi-dash-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE POS SUMMARY -->
            <div class="col-md-4">

                <div class="card shadow-sm border-0 sticky-top" style="top: 80px;">
                    <div class="card-header bg-dark text-white">
                        <strong><i class="bi bi-receipt"></i> Billing Summary</strong>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>₹ <span id="subtotal">0.00</span></strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="discount_amount" id="discount" class="form-control text-end"
                                    placeholder="Enter discount" min="0"
                                    oninput="this.value = Math.abs(this.value); grandTotal();">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax / GST</span>
                            <strong>₹ <span id="tax_amount">0.00</span></strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Grand Total</h5>
                            <h4 class="mb-0 text-success fw-bold">
                                ₹ <span id="grand_total">0.00</span>
                            </h4>
                        </div>

                        <button class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-save"></i> Save Invoice
                        </button>

                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.product-select').select2({ width: '100%' });
            $('.customer-select').select2({ width: '100%' });

            $(document).on('change', '.product-select', function () {
                let selected = $(this).find(':selected');

                let price = selected.data('price') || 0;
                let gst = selected.data('gst') || 0;

                let row = $(this).closest('tr');

                row.find('[name="price[]"]').val(price);
                row.find('[name="gst_percent[]"]').val(gst);

                calc(this);
            });
        });

        function addRow() {
            $('.product-select').select2('destroy');

            let row = $('#table tbody tr:first').clone();

            row.find('input[name="qty[]"]').val(1);
            row.find('input[name="price[]"]').val(0);
            row.find('input[name="total[]"]').val(0);
            row.find('input[name="gst_percent[]"]').val(0);
            row.find('input[name="gst_amount[]"]').val(0);
            row.find('select').val('');

            $('#table tbody').append(row);

            $('.product-select').select2({ width: '100%' });

            grandTotal();
        }

        function calc(el) {
            let row = $(el).closest('tr');

            let qty = parseFloat(row.find('[name="qty[]"]').val()) || 0;
            let price = parseFloat(row.find('[name="price[]"]').val()) || 0;
            let gstPercent = parseFloat(row.find('[name="gst_percent[]"]').val()) || 0;

            let amount = qty * price;
            let gstAmount = amount * gstPercent / 100;
            let total = amount + gstAmount;

            row.find('[name="gst_amount[]"]').val(gstAmount.toFixed(2));
            row.find('[name="total[]"]').val(total.toFixed(2));

            grandTotal();
        }

        function grandTotal() {
            let subtotal = 0;
            let gstTotal = 0;

            $('#table tbody tr').each(function () {
                let row = $(this);

                let qty = parseFloat(row.find('[name="qty[]"]').val()) || 0;
                let price = parseFloat(row.find('[name="price[]"]').val()) || 0;
                let gstAmount = parseFloat(row.find('[name="gst_amount[]"]').val()) || 0;

                subtotal += qty * price;
                gstTotal += gstAmount;
            });

            let discount = parseFloat($('#discount').val()) || 0;

            if (discount < 0) {
                discount = 0;
                $('#discount').val('');
            }

            let finalTotal = subtotal + gstTotal - discount;

            if (finalTotal < 0) {
                finalTotal = 0;
            }

            $('#subtotal').text(subtotal.toFixed(2));
            $('#tax_amount').text(gstTotal.toFixed(2));
            $('#grand_total').text(finalTotal.toFixed(2));
        }

        function removeRow(btn) {
            if ($('#table tbody tr').length > 1) {
                $(btn).closest('tr').remove();
                grandTotal();
            }
        }
    </script>
@endpush