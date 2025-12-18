<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{

    public function login(array $credentials): bool
    {
        return Auth::guard('admin')->attempt($credentials);
    }
}
