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
}
