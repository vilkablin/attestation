<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{

  public function update(Request $request)
  {
    $user = Auth::user();

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'phone' => 'required|string|max:255|unique:users,phone,' . $user->id,
      'current_password' => 'required_with:new_password',
      'new_password' => 'nullable|min:8|confirmed',
    ]);

    $user->name = $validated['name'];
    $user->phone = $validated['phone'];

    if ($request->new_password) {
      if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
      }
      $user->password = Hash::make($request->new_password);
    }

    $user->save();

    return redirect()->back()->with('success', 'Профиль обновлен');
  }


  public function uploadImage(Request $request)
  {
    $request->validate([
      'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = Auth::user();


    if ($user->image) {
      Storage::disk('public')->delete($user->image);
    }


    $path = $request->file('image')->store('avatars', 'public');
    $user->update(['image' => $path]);


    return back()->with([
      'success' => 'Аватар успешно обновлен',
      'user' => $user->fresh()
    ]);
  }
}
