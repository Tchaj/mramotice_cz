<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('email', $request->input('email'))
            ->first();
        if (empty($user)) {
            return $this->handleFailed($this->error::user_not_exists, 404);
        }
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        if (Auth::attempt($credentials, $request->input("remember") ?? false)) {
                $userData = $request->user();
                $tokenResult = $userData->createToken('Personal Access Token');
                return $this->handleSuccess([
                    "access_token" => $tokenResult->accessToken
                ], "success_login");
        } else {
            return $this->handleFailed($this->error::invalid_login);
        }
    }
}
