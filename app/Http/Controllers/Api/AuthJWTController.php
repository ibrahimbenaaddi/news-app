<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\loginRequest;
use App\Http\Resources\AdminResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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
                    401
                );
            }
            // for refreshToken
            $expiration = 60 * 8 ;// 8 Houre
            $refreshToken = auth('api')->setTTL($expiration)->fromUser(auth('api')->user()); // use FromUser to generate Jwt token from the Auth admin
            return response()->json(
                [
                    'status' => true,
                    'admin'  => new AdminResource(Auth::guard('api')->user())
                ],
                200
            )->cookie('token',$token)
             ->cookie('refreshToken',$refreshToken);
            
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

    public function logout(Request $request)
    {
        try {
            $refreshToken = $request->cookie('refreshToken');
            auth('api')->logout();
            auth('api')->setToken($refreshToken)->logout();
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Admin logged out successfully'
                ],
                200
            )->cookie('token','',-1)->cookie('refreshToken','',-1);
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

    public function refreshToken()
    {
        $newAccessToken = auth('api')->fromUser(auth('api')->user()) ;
        return response()->json(status: 204)->cookie('token',$newAccessToken);
    }
}
