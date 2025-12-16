<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthService
{

    public function login(array $credentials): bool
    {
        try {
            if(Auth::attempt($credentials)){
                return true;
            }
            return false;
        } catch (Exception $err) {
            Log::error('The Error  in loginService (login) is :' . $err->getMessage());
            return false;
        }
    }
}
