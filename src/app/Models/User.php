<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Message\ErrorMessage;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use ApiResponse, HasApiTokens, HasFactory, Notifiable;

    public static $required = [
        "email",
        "password"
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'phone',
        'firstname',
        'lastname',
        'titlenamebefore',
        'titlenameafter',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_old',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'boolean',
    ];


    public static function isLogged()
    {
        if(!Auth::check()){
            return (new User)->handleFailed(ErrorMessage::not_authorized);
            exit;
        }
        return true;
    }

    public static function getGenderSaluteEmail(string $gender): string
    {
        if($gender == "male"){
            $gender = "pane";
        }else if($gender == "female"){
            $gender = "paní";
        }else if($gender == "miss"){
            $gender = "slečno";
        }else if($gender == "it"){
            $gender = "ono";
        }else{
            $gender = "";
        }

        return $gender;
    }
}
