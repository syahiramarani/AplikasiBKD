<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ===================== INDEX =====================
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    // ===================== CREATE =====================
    public function create()
    {
        return view('user.create');
    }

    // ===================== STORE =====================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect('/User')->with('success', 'User berhasil ditambahkan');
    }

    // ===================== UPDATE =====================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/User')->with('success', 'User berhasil diupdate');
    }
    // ===================== DELETE =====================
    public function delete($id)
    {
        User::findOrFail($id)->delete();

        return redirect('/User')
            ->with('success', 'Data berhasil dihapus');
    }
}