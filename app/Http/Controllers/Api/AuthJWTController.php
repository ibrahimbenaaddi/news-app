<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\loginRequest;
use App\Http\Resources\AdminResource;
use App\Traits\ApiResponses;
use App\Traits\Helper;
use App\Traits\LogError;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuthJWTController extends Controller
{
    use ApiResponses, LogError, Helper;

    public function login(loginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $token = Auth::guard('api')->attempt($credentials); // return token JWT or false
            if (!$token) {
                return self::failedLogin();
            }
            // for refreshToken
            $expiration = 60 * 8; // 8 Houre
            $refreshToken = auth('api')->setTTL($expiration)->fromUser(auth('api')->user()); // use FromUser to generate Jwt token from the Auth admin

            return self::successLogin(new AdminResource(Auth::guard('api')->user()), $token, $refreshToken);
        } catch (Exception $err) {
            self::theLog('login', 'AuthJWTController', $err);
            return self::failedLogin();
        }
    }

    public function logout(Request $request)
    {
        try {
            $refreshToken = $request->cookie('refreshToken');
            auth('api')->logout();
            auth('api')->setToken($refreshToken)->logout();
            return self::successLogout();
        } catch (Exception $err) {
            self::theLog('logout', 'AuthJWTController', $err);
            return self::successLogout(); // even if has some error log the admin
        }
    }

    public function refreshToken()
    {
        $newAccessToken = auth('api')->fromUser(auth('api')->user());
        return response()->json(status: 204)->cookie('token', $newAccessToken);
    }
}
