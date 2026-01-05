<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\loginRequest;
use App\Http\Resources\AdminResource;
use Exception;
use Illuminate\Support\Facades\Log;


class AuthJWTController extends Controller
{
    private string $ERROR = 'your credentials is Worng !!';
    public function login(loginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $token = Auth::guard('api')->attempt($credentials); // return token JWT or false

            if ( !$token  ) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $this->ERROR
                    ],
                    500
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'admin'  => new AdminResource(Auth::guard('api')->user()),
                    'token' => $token
                ],
                200
            );
            
        } catch (Exception $err) {
            Log::error('The Error in AuthController(login) in api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => $this->ERROR,
                ],
                404
            );
        }
    }

    public function logout()
    {
        try {
            auth()->invalidate();
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Admin logged out successfully'
                ],
                200
            );
        } catch (Exception $err) {
            Log::error('The Error in AuthController(logout) in api : ' . $err->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => $err->getMessage()
                ],
                404
            );
        }
    }
}
