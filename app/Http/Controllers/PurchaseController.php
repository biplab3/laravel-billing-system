<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::where('user_id', Auth::id())
            ->with('supplier')
            ->latest()
            ->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('user_id', Auth::id())->orderBy('name')->get();
        $products = Product::where('user_id', Auth::id())->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'price' => 'required|array',
            'amount' => 'required|array',
        ]);

        $supplier = Supplier::where('id', $request->supplier_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $validItems = [];

            foreach ($request->product_id as $key => $productId) {
                if (!$productId) {
                    continue;
                }

                $product = Product::where('id', $productId)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                $qty = (float) ($request->qty[$key] ?? 0);
                $price = (float) ($request->price[$key] ?? 0);
                $amount = (float) ($request->amount[$key] ?? ($qty * $price));

                if ($qty <= 0 || $price < 0) {
                    throw new \Exception('Invalid quantity or price found.');
                }

                $totalAmount += $amount;

                $validItems[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'price' => $price,
                    'amount' => $amount,
                ];
            }

            if (count($validItems) == 0) {
                throw new \Exception('Please select at least one product.');
            }

            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'supplier_id' => $supplier->id,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $totalAmount,
            ]);

            foreach ($validItems as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product']->id,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'amount' => $item['amount'],
                ]);

                // important logic: purchase increases stock
                $item['product']->stock_quantity += $item['qty'];
                $item['product']->save();
            } //try block ends

            DB::commit();

            return redirect()->route('purchases.show', $purchase->id)
                ->with('success', 'Purchase entry saved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $purchase = Purchase::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('supplier', 'items.product')
            ->firstOrFail();

        return view('purchases.show', compact('purchase'));
    }


    public function purchaseReport(Request $request)
    {
        $suppliers = Supplier::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        $query = Purchase::where('user_id', Auth::id())
            ->with('supplier');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('purchase_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('purchase_date', '<=', $request->to_date);
        }

        $purchases = $query->latest()->get();

        $totalPurchaseAmount = $purchases->sum('total_amount');
        $totalPurchaseEntries = $purchases->count();

        return view('purchases.report', compact(
            'suppliers',
            'purchases',
            'totalPurchaseAmount',
            'totalPurchaseEntries'
        ));
    }

    public function exportPurchaseReport(Request $request)
    {
        $query = Purchase::where('user_id', Auth::id())
            ->with('supplier');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('purchase_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('purchase_date', '<=', $request->to_date);
        }

        $purchases = $query->latest()->get();

        $fileName = 'purchase_report_' . date('Y_m_d_H_i_s') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($purchases) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Purchase No',
                'Supplier',
                'Purchase Date',
                'Total Amount'
            ]);

            foreach ($purchases as $purchase) {
                fputcsv($file, [
                    'PUR-' . str_pad($purchase->id, 5, '0', STR_PAD_LEFT),
                    $purchase->supplier->name ?? '-',
                    \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y'),
                    $purchase->total_amount,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}