<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User Created Successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User Updated Successfully');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();

        return back()
            ->with('success', 'User Deleted Successfully');
    }


}
