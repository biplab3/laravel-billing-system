<!DOCTYPE html>
<html>

<head>
    <title>Thermal Receipt</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .receipt {
            width: 80mm;
            background: #fff;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            padding: 3px 0;
        }

        .print-btn {
            display: block;
            width: 80mm;
            margin: 15px auto;
            padding: 8px;
            background: #198754;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }

            .print-btn {
                display: none;
            }

            .receipt {
                width: 80mm;
                margin: 0;
                padding: 5px;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="print-btn">
        Print Receipt
    </button>

    <div class="receipt">

        <div class="text-center">
            <h3 style="margin: 0;">BillingApp</h3>
            <div>Sales Receipt</div>
            <div>Thank you for shopping</div>
        </div>

        <div class="line"></div>

        <table>
            <tr>
                <td>Invoice:</td>
                <td class="text-right bold">
                    INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
            <tr>
                <td>Date:</td>
                <td class="text-right">
                    {{ $invoice->created_at->format('d-m-Y h:i A') }}
                </td>
            </tr>
            <tr>
                <td>Customer:</td>
                <td class="text-right">
                    {{ $invoice->customer->name ?? '-' }}
                </td>
            </tr>
            @if(!empty($invoice->customer->mobile))
                <tr>
                    <td>Mobile:</td>
                    <td class="text-right">
                        {{ $invoice->customer->mobile }}
                    </td>
                </tr>
            @endif
        </table>

        <div class="line"></div>

        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">Item</th>
                    <th>Qty</th>
                    <th class="text-right">Amt</th>
                </tr>
            </thead>

            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td style="text-align:left;">
                            {{ $item->product->name ?? '-' }}
                            <br>
                            <small>
                                ₹{{ number_format($item->price, 2) }}
                            </small>
                        </td>
                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>
                        <td class="text-right">
                            ₹{{ number_format($item->total, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="line"></div>

        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">
                    ₹{{ number_format($invoice->total_amount, 2) }}
                </td>
            </tr>

            <tr>
                <td>GST</td>
                <td class="text-right">
                    ₹{{ number_format($invoice->gst_amount, 2) }}
                </td>
            </tr>

            <tr>
                <td>Discount</td>
                <td class="text-right">
                    ₹{{ number_format($invoice->discount_amount ?? 0, 2) }}
                </td>
            </tr>

            <tr>
                <td class="bold">Grand Total</td>
                <td class="text-right bold">
                    ₹{{ number_format($invoice->final_amount, 2) }}
                </td>
            </tr>
        </table>

        <div class="line"></div>

        <div class="text-center">
            <p style="margin: 4px 0;">*** Thank You ***</p>
            <small>This is a computer-generated bill</small>
        </div>

    </div>

</body>

</html>