<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function submitForgetPasswordForm(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Resetování hesla');
            });

            return $this->handleSuccess([], "forgoten_password_send");
        }catch(Exception $exception)
        {
            return $this->handleFailed("not_email_exists");
        }
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $userData = User::where("email", $request->email)->first();
        if($userData){
            if(Hash::make($request->password) == $userData->password_old){
                $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password), 'password_old' => Hash::make($request->password)]);
                    DB::table('password_resets')->where(['email'=> $request->email])->delete();
            }else{
                return $this->handleFailed("password_old_is_exists");
            }
        }else{
            return $this->handleFailed("user_not_exists");
        }

        return $this->handleSuccess([], "password_changed_success");
    }

}
