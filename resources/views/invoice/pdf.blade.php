@extends('layouts.app')

@section('content')
    <h2>Invoice</h2>

    <p><strong>Customer:</strong> {{ $invoice->customer->name }}</p>

    <table border="1" width="100%" cellpadding="8">
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>

        @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->total }}</td>
            </tr>
        @endforeach

    </table>

    <h3>Total: ₹ {{ $invoice->final_amount }}</h3>
@endsection