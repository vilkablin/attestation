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
      'phone' => [
        'required',
        'regex:/^((\+7|7|8)+([0-9]){10})$/',
        'unique:users,phone',
      ],
      'name' => [
        'required',
        'string',
        'max:255',
        'regex:/^[А-Яа-яЁё\s\-]+$/u',
      ],
      'password' => [
        'required',
        'string',
        'min:8',
        'confirmed',
      ],
    ], [
      'phone.required' => 'Телефон обязателен для заполнения.',
      'phone.regex' => 'Введите корректный номер телефона (например, +79001234567).',
      'phone.unique' => 'Этот номер уже зарегистрирован.',

      'name.required' => 'Имя обязательно для заполнения.',
      'name.regex' => 'Имя должно содержать только кириллицу.',

      'password.required' => 'Пароль обязателен.',
      'password.min' => 'Пароль должен содержать не менее 8 символов.',
      'password.confirmed' => 'Пароли не совпадают.',
    ]);


    $user = User::create([
      'phone' => $request->phone,
      'name' => $request->name,
      'password' => Hash::make($request->password),
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
      'phone' => [
        'required',
        'regex:/^((\+7|7|8)+([0-9]){10})$/',
      ],
      'password' => 'required|string',
    ], [
      'phone.required' => 'Введите номер телефона.',
      'phone.regex' => 'Введите корректный номер телефона.',
      'password.required' => 'Введите пароль.',
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
