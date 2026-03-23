<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Models\Audit;
use App\Models\User;
use App\ResolvesJti;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ResolvesJti;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        $token = Auth::attempt($credentials);

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::with(['roles.permissions'])->find(Auth::id());

        // log activity
        $this->logActivity($user, 'login', 'logged_in');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => now()->addMinutes(config('jwt.ttl')),
            'user' => $user,
        ]);
    }

    public function logout()
    {
        $user = User::findOrFail(Auth::id());
        // log activity
        $this->logActivity($user, 'logout', 'logged_out');

        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return response()->json([
            'access_token' => Auth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => now()->addMinutes(config('jwt.refresh_ttl')),
        ]);
    }

    public function profile()
    {
        $user = User::with(['roles.permissions'])->findOrFail(Auth::id());

        return response()->json($user);
    }

    private function logActivity(User $user, string $event, string $description): void
    {
        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->event($event)
            ->tap(function (Audit $audit) {
                $audit->ip_address = request()->ip();
                $audit->session_id = $this->resolveJti();
            })
            ->log($description);
    }
}
