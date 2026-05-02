<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LoginAuthRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * Handle login request and return a session token.
     *
     * @param LoginAuthRequest $request The validated login request containing 'email' and 'password'.
     * @return JsonResponse A JSON response containing the session token if login is successful, or an error message if it fails.
     */
    public function login(LoginAuthRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $email = $validated['email'];
        $password = $validated['password'];

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        $sessionToken = $user->createToken('auth_token')->plainTextToken;

        return $this->success($sessionToken, 'Login successful');
    }

    /**
     * Handle login request and return a session token.
     *
     * @param Request $request The request containing the 'auth_token' to verify.
     * @return JsonResponse A JSON response indicating whether the session is active and valid, along with user data if successful, or an error message if it fails.
     */
    public function verifySession(Request $request): JsonResponse
    {
        $user = $request->user();

        info('Session verification attempted for user ID: ' . ($user ? $user->id : 'null'));

        if ($user) {
            return $this->success($user, 'Session is active and valid');
        }

        return $this->error('Session is inactive or invalid', 401);
    }

    /**
     * Handle login request and return a session token.
     *
     * @param Request $request The request to log out the user, which will invalidate the current session.
     * @return JsonResponse A JSON response indicating that the logout was successful, or an error message if it fails.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            // delete ONLY current token
            $user->currentAccessToken()->delete();
        }

        return $this->success(null, 'Logout successful');
    }
}
