<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{

    private $service;

    public  function __construct()
    {
        $this->service = new AuthService;
    }

    public function loginPanel()
    {
        return view('login');
    }

    public function login(loginRequest $request)
    {
        try {
            $credentials = $request->validated();
            if (!$this->service->login($credentials)) {
                Log::warning('warning Some one try to enter to Dashboard of Admin ');
                return back()->with('failed', 'your credentials is worng ');
            };
            return 'welcome to Dashboard';
        } catch (Exception $err) {
            Log::error('The Error  in AuthController (login) is :' . $err->getMessage());
            return redirect()->route('login.form');
        }
    }
}
