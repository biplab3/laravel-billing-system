<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('user_id', Auth::id())->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        Customer::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'user_id' => Auth::id()
        ]);

        return redirect('/customers');
    }

    public function edit($id)
    {
        $customer = Customer::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $customer->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
        ]);

        return redirect('/customers')->with('success', 'Customer updated successfully');
    }

    public function delete($id)
    {
        $customer = Customer::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $customer->delete();

        return redirect('/customers')->with('success', 'Customer deleted successfully');
    }
}
