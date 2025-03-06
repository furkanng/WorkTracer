<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        if (auth()->user()->isAdmin()) {
            return route('admin.dashboard');
        } elseif (auth()->user()->isSecretary()) {
            return route('secretary.dashboard');
        }
        return route('technician.dashboard');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
} 