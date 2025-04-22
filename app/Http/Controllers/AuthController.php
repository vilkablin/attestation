<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function showRegistrationForm()
  {
    return Inertia::render('Auth/Signup');
  }

  public function register(Request $request)
  {
    $request->validate([
      'phone' => 'required|string|max:255|unique:users',
      'name' => 'required|string|max:255',
      'password' => 'required|string|min:8|confirmed',
      'telegram_id' => 'nullable|string',
    ]);

    $user = User::create([
      'phone' => $request->phone,
      'name' => $request->name,
      'password' => Hash::make($request->password),
      'telegram_id' => $request->telegram_id,
      'role_id' => 1, // Обычный пользователь
    ]);

    Auth::login($user);

    return redirect()->intended('/dashboard');
  }

  public function showLoginForm()
  {
    return Inertia::render('Auth/Signin');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'phone' => 'required|string',
      'password' => 'required',
    ]);

    if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']], $request->remember)) {
      $request->session()->regenerate();
      return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
      'phone' => 'Неверные учетные данные.',
    ]);
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
  }
}
