<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gst_no' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        Supplier::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'gst_no' => $request->gst_no,
            'address' => $request->address,
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully');
    }

    public function edit($id)
    {
        $supplier = Supplier::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gst_no' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        $supplier->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'gst_no' => $request->gst_no,
            'address' => $request->address,
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    public function delete($id)
    {
        $supplier = Supplier::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully');
    }

}
