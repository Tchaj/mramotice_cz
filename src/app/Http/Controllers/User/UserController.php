<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        User::isLogged();
        $user = Auth::user();
        return $this->handleSuccess($user->toArray(), "User data");
    }
}
