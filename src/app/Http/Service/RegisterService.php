<?php
/***
 * File: RegisterService.php
 * Product: mramotice_cz
 * HeroldCMS | heroldcms.eu
 * Author: Martin Horáček | martinhoracek.cz
 * Date: 01.02.2023
 * Time: 19:08
 * Codes may not be redistributed the owner's consent.
 * Copyrights (c) 2023. All right Reserved
 ***/


namespace App\Http\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public static function registerUser($request){

        $user = new User();
        $user->email            = $request['email'];
        $user->titlenamebefore  = $request['titlenamebefore'] ?? "";
        $user->firstname        = $request['firstname'] ?? "";
        $user->lastname         = $request['lastname'] ?? "";
        $user->titlenameafter   = $request['titlenameafter'] ?? "";
        $user->gender           = $request['gender'] ?? "undefined";
        $user->phone            = $request['phone']  ?? 0 ;
        $user->role             = $request['role'] ?? "user" ;
        $user->password         = Hash::make( $request['password'] );
        $user->password_old     = Hash::make( $request['password']);
        $user->save();
        return $user;
    }
}
