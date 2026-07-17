<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email|max:50',
      'password' => 'required|max:50',
    ]);
    if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
      if (Auth::user()->role == 'kajur')
        return redirect('/kajur');
      return redirect('/dashboard');
    }
    return back()->with('failed', 'Email atau password salah');
  }
  function register(Request $request)
  {
    $request->validate([
      'name' => 'required|max:50',
      'email' => 'required|email|max:50',
      'password' => 'required|min:8|max:50',
      'confirm_password' => 'required|min:8|max:50|same:password',

    ]);
    $request['status'] = "verify";
    $user = User::create($request->all());
    Auth::login($user);
    return redirect('/dashboard');

  }

  public function logout()
  {
    Auth::logout();
    return redirect('/login');
  }
}
