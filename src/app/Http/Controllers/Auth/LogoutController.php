<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function index(){
        User::isLogged();
        Auth::user()->token()->revoke();
        return $this->handleSuccess([
            'access_token' => null
        ], $this->error::user_logout);
    }
}
