<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::find(Auth::id());

        // log activity
        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->log('logged_in');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user
        ]);
    }

    public function logout()
    {
        $user = User::findOrFail(Auth::id());
        // log activity
        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->log('logged_out');

        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return response()->json([
            'access_token' => Auth::refresh(),
            'token_type' => 'bearer',
        ]);
    }

    public function profile()
    {
        return response()->json(Auth::user());
    }
}
