<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'gst_percent' => $request->gst_percent,
            'stock_quantity' => $request->stock_quantity,
            'user_id' => Auth::id()
        ]);

        return redirect('/products')->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'gst_percent' => 'nullable|numeric'
        ]);

        $product = Product::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $product->update($request->all());

        return redirect('/products')->with('success', 'Product updated');
    }

    public function delete($id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $product->delete();

        return back()->with('success', 'Product deleted');
    }

    public function stockReport(Request $request)
    {
        $query = Product::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();

        $totalProducts = $products->count();

        $totalStockQty = $products->sum('stock_quantity');

        $inventoryValue = $products->sum(function ($product) {
            return $product->stock_quantity * $product->price;
        });

        return view('products.stock_report', compact(
            'products',
            'totalProducts',
            'totalStockQty',
            'inventoryValue'
        ));
    }

    public function exportStockReport(Request $request)
    {
        $query = Product::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();

        $filename = 'stock_report_' . date('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($products) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Product',
                // 'SKU',
                'Stock Qty',
                'Price',
                'Stock Value'
            ]);

            foreach ($products as $product) {

                fputcsv($file, [
                    $product->name,
                    // $product->sku,
                    $product->stock_quantity,
                    $product->price,
                    $product->stock_quantity * $product->price,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
