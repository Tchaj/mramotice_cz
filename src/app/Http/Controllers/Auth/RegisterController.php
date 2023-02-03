<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Service\RegisterService;
use App\Http\Service\ValidateService;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request){
        $validate = self::validator($request->all());
        if($validate->fails()){
            return $this->handleFailed($validate->errors()->first(), 400);
        }else{
                $user = RegisterService::registerUser($request->all());
                $token = Str::random(64);
                UserVerify::create([
                    'user_id' => $user->id,
                    'token' => $token
                ]);
                Mail::send('email.emailVerificationEmail', ['token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Potvrzení e-mailu');
                });
                return $this->registered($request, $user);

        }
    }

    protected function registered(Request $request, $user): JsonResponse
    {

        return $this->handleSuccess($request->all(), $this->error::user_register_success);
    }

    protected function validator($data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, [
            'phone' => 'integer|max:9|min:9',
            'role' => 'string',
            'titlenamebefore' => 'string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'titlenameafter' => 'string|max:255',
            'gender' => 'string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
}
