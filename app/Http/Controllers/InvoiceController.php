<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function create()
    {
        // ✅ Only logged-in user data
        $products = Product::where('user_id', Auth::id())->get();
        $customers = Customer::where('user_id', Auth::id())->get();

        return view('invoice.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'total' => 'required|array',
            'gst_amount' => 'nullable|array',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);
        //dd($request);
        $customer = Customer::where('id', $request->customer_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $subtotal = 0;
        $gstTotal = 0;
        $validItems = [];

        // ✅ First loop: validate stock + calculate totals
        foreach ($request->product_id as $key => $productId) {
            if (!$productId) {
                continue;
            }

            $product = Product::where('id', $productId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $qty = (float) ($request->qty[$key] ?? 0);
            $price = (float) ($request->price[$key] ?? 0);
            $gstAmount = (float) ($request->gst_amount[$key] ?? 0);
            $lineTotal = (float) ($request->total[$key] ?? ($qty * $price));

            if ($qty <= 0) {
                return back()->withErrors('Quantity must be greater than 0 for ' . $product->name)->withInput();
            }

            // ✅ Stock check
            if ($product->stock_quantity < $qty) {
                return back()
                    ->withErrors('Not enough stock for ' . $product->name . '. Available stock: ' . $product->stock_quantity)
                    ->withInput();
            }

            $subtotal += ($qty * $price);
            $gstTotal += $gstAmount;

            $validItems[] = [
                'product' => $product,
                'quantity' => $qty,
                'price' => $price,
                'total' => $lineTotal,
            ];
        }

        if (count($validItems) == 0) {
            return back()->withErrors('Please select at least one product')->withInput();
        }

        $discount = max(0, (float) ($request->discount_amount ?? 0));
        $finalAmount = max(0, ($subtotal + $gstTotal) - $discount);

        // ✅ Create invoice only after stock validation passes
        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'total_amount' => $subtotal,
            'gst_amount' => $gstTotal,
            'discount_amount' => $discount,
            'final_amount' => $finalAmount,
            'user_id' => Auth::id()
        ]);

        // ✅ Second loop: save items + reduce stock
        foreach ($validItems as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);

            // ✅ Reduce stock
            $item['product']->stock_quantity -= $item['quantity'];
            $item['product']->save();
        }

        //main loop ends

        return redirect()->route('invoice.show', $invoice->id)
            ->with('success', 'Invoice created successfully');
    }

    public function store_28_04_2026(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'total' => 'required|array',
            'gst_amount' => 'nullable|array',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $customer = Customer::where('id', $request->customer_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $subtotal = 0;
        $gstTotal = 0;

        foreach ($request->product_id as $key => $productId) {
            if (!$productId) {
                continue;
            }

            $product = Product::where('id', $productId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $qty = (float) ($request->qty[$key] ?? 0);
            $price = (float) ($request->price[$key] ?? 0);
            $gstAmount = (float) ($request->gst_amount[$key] ?? 0);

            $subtotal += $qty * $price;
            $gstTotal += $gstAmount;
        }

        $discount = max(0, (float) ($request->discount_amount ?? 0));
        $finalAmount = max(0, ($subtotal + $gstTotal) - $discount);

        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'total_amount' => $subtotal,
            'gst_amount' => $gstTotal,
            'discount_amount' => $discount,
            'final_amount' => $finalAmount,
            'user_id' => Auth::id()
        ]);

        foreach ($request->product_id as $key => $productId) {
            if (!$productId) {
                continue;
            }

            $product = Product::where('id', $productId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'quantity' => $request->qty[$key],
                'price' => $request->price[$key],
                'total' => $request->total[$key],
            ]);
        }

        return redirect()->route('invoice.show', $invoice->id)
            ->with('success', 'Invoice created successfully');
    }

    public function store_24_04_2026(Request $request)
    {
        // ✅ Validate
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'total' => 'required|array',
        ]);

        // ✅ Ensure customer belongs to user
        $customer = Customer::where('id', $request->customer_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // ✅ Create invoice
        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'total_amount' => array_sum($request->total),
            'gst_amount' => 0,
            'final_amount' => array_sum($request->total),
            'user_id' => Auth::id()
        ]);

        // ✅ Save items
        foreach ($request->product_id as $key => $productId) {

            // Ensure product belongs to user
            $product = Product::where('id', $productId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'quantity' => $request->qty[$key],
                'price' => $request->price[$key],
                'total' => $request->total[$key],
            ]);
        }

        return redirect()->route('invoice.show', $invoice->id);
    }

    public function show($id)
    {
        // ✅ Only allow owner to view
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items.product', 'customer')
            ->firstOrFail();

        return view('invoice.show', compact('invoice'));
    }

    public function downloadPDF($id)
    {
        // ✅ Secure access
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items.product', 'customer')
            ->firstOrFail();

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));

        return $pdf->download('invoice_' . $id . '.pdf');
    }

    public function receipt($id)
    {
        $invoice = Invoice::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('items.product', 'customer')
            ->firstOrFail();

        return view('invoice.receipt', compact('invoice'));
    }

    public function index(Request $request)
    {
        $query = Invoice::where('user_id', Auth::id())
            ->with('customer');

        if ($request->filled('invoice_no')) {
            $invoiceNo = str_replace('INV-', '', strtoupper($request->invoice_no));
            $invoiceId = (int) ltrim($invoiceNo, '0');

            if ($invoiceId > 0) {
                $query->where('id', $invoiceId);
            }
        }

        if ($request->filled('customer')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();

        return view('invoice.index', compact('invoices'));
    }

    public function index_25_05_2026()
    {
        $invoices = Invoice::where('user_id', Auth::id())
            ->with('customer')
            ->latest()
            ->get();

        return view('invoice.index', compact('invoices'));
    }

    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items.product', 'customer')
            ->firstOrFail();

        $products = Product::where('user_id', Auth::id())->get();
        $customers = Customer::where('user_id', Auth::id())->get();

        return view('invoice.edit', compact('invoice', 'products', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'total' => 'required|array',
            'gst_amount' => 'nullable|array',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $customer = Customer::where('id', $request->customer_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $subtotal = 0;
        $gstTotal = 0;

        foreach ($request->product_id as $key => $productId) {
            if (!$productId) {
                continue;
            }

            $product = Product::where('id', $productId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $qty = (float) ($request->qty[$key] ?? 0);
            $price = (float) ($request->price[$key] ?? 0);
            $gstAmount = (float) ($request->gst_amount[$key] ?? 0);

            $subtotal += $qty * $price;
            $gstTotal += $gstAmount;
        }

        $discount = max(0, (float) ($request->discount_amount ?? 0));
        $finalAmount = max(0, ($subtotal + $gstTotal) - $discount);

        $invoice->update([
            'customer_id' => $customer->id,
            'total_amount' => $subtotal,
            'gst_amount' => $gstTotal,
            'discount_amount' => $discount,
            'final_amount' => $finalAmount,
        ]);


        $invoice->items()->delete();

        foreach ($request->product_id as $key => $productId) {
            if (!$productId) {
                continue;
            }

            $product = Product::where('id', $productId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'quantity' => $request->qty[$key],
                'price' => $request->price[$key],
                'total' => $request->total[$key],
            ]);
        } //loop ends

        return redirect()->route('invoice.show', $invoice->id)
            ->with('success', 'Invoice updated successfully');
    }

    public function delete($id)
    {
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoice.index')
            ->with('success', 'Invoice deleted successfully');
    }


    public function exportCsv(Request $request)
    {
        $query = Invoice::where('user_id', Auth::id())
            ->with(['customer', 'items.product']);

        if ($request->filled('invoice_no')) {
            $invoiceNo = str_replace('INV-', '', strtoupper($request->invoice_no));
            $invoiceId = (int) ltrim($invoiceNo, '0');

            if ($invoiceId > 0) {
                $query->where('id', $invoiceId);
            }
        }

        if ($request->filled('customer')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $invoices = $query->latest()->get();

        $fileName = 'invoice_item_report_' . date('Y_m_d_H_i_s') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Invoice No',
                'Customer',
                'Product',
                'Quantity',
                'Price',
                'Item Total',
                'Invoice Subtotal',
                'Invoice GST',
                'Invoice Discount',
                'Invoice Final Total',
                'Invoice Date'
            ]);

            foreach ($invoices as $invoice) {
                foreach ($invoice->items as $item) {
                    fputcsv($file, [
                        'INV-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT),
                        $invoice->customer->name ?? '-',
                        $item->product->name ?? '-',
                        $item->quantity,
                        $item->price,
                        $item->total,
                        $invoice->total_amount,
                        $invoice->gst_amount,
                        $invoice->discount_amount ?? 0,
                        $invoice->final_amount,
                        $invoice->created_at->format('d-m-Y'),
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    //function for sales report

    public function salesReport(Request $request)
    {
        $query = Invoice::where('user_id', Auth::id())
            ->with('customer');

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $invoices = $query->latest()->get();

        $grandTotal = $invoices->sum('final_amount');

        return view(
            'invoice.sales_report',
            compact('invoices', 'grandTotal')
        );
    }

    public function customerLedger(Request $request)
    {
        $customers = Customer::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        $invoices = collect();
        $totalSales = 0;
        $selectedCustomer = null;

        if ($request->filled('customer_id')) {

            $selectedCustomer = Customer::where('id', $request->customer_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $query = Invoice::where('user_id', Auth::id())
                ->where('customer_id', $selectedCustomer->id)
                ->with('customer');

            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            $invoices = $query->latest()->get();

            $totalSales = $invoices->sum('final_amount');
        }

        return view('invoice.customer_ledger', compact(
            'customers',
            'invoices',
            'totalSales',
            'selectedCustomer'
        ));
    }

}
