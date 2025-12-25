<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Resources\AdminResource;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    private string $ERROR = 'your credentials is Worng !!';
    public function login(loginRequest $request)
    {
        try {
            $credentials = $request->validated();
            if (!Auth::guard('admin')->attempt($credentials)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $this->ERROR,
                    ],
                    500
                );
            }

            $token = Auth::guard('admin')->user()->createToken('api', ['isAdmin'], now()->plus(hours: 8))->plainTextToken;
            return response()->json(
                [
                    'status' => true,
                    'admin'  => new AdminResource(Auth::guard('admin')->user()),
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

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
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
                    'message' => 'we failed to login'
                ],
                404
            );
        }
    }
}
