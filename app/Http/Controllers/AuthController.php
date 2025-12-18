<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Models\Admin;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    private $service;

    public  function __construct()
    {
        $this->middleware('isLog')->except('logout');
        $this->service = new AuthService;
    }

    public function loginPanel()
    {
        return view('login');
    }

    public function login(loginRequest $request)
    {
        $credentials = $request->validated();
        if (!$this->service->login($credentials)) {
            Log::warning('warning Some one try to enter to Dashboard of Admin ');
            return back()->with('failed', 'your credentials is worng ');
        };
        $request->session()->regenerate();
        $request->session()->put('adminID', Auth::guard('admin')->id());
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
