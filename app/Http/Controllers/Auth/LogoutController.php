<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke()
    {
        auth()->guard(config('filament.auth.guard'))->logout();
        return redirect()->route('filament.auth.login');
    }
}
