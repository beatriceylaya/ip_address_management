<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\RolesEnum;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function __invoke(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());

        $user->assignRole(RolesEnum::USER);

        $token = Auth::login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
        ]);
    }
}
