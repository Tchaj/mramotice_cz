<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class VerificationController extends Controller
{
    public function getToken(int $id){
        $verifyUser = UserVerify::where('user_id', $id)->first();
        return $verifyUser->token;
    }
    public function verifyAccount($token): \Illuminate\Http\JsonResponse
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->email_verified_at) {
                $verifyUser->user->email_verified_at = 1;
                $verifyUser->user->save();
                $gender = User::getGenderSaluteEmail($user->gender);
                $dataUser = [
                    "titlenamebefore" => $user->titlenamebefore,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "titlenameafter" => $user->titlenamebefore,
                    "gender" => $gender
                ];
                DB::table('users_verify')->where(['user_id'=> $user->id])->delete();
                Mail::send('email.welcome', $dataUser, function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Vítejte na portálu '.env("APP_NAME"));
                });
                return $this->handleSuccess($verifyUser->toArray(), "email_verified");
            } else {
                return $this->handleFailed("verified_is_already");
            }
        }else{
            return $this->handleFailed("verified_email_failed");
        }

    }

}
