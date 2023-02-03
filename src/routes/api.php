<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [LoginController::class, 'login']);

Route::group(['prefix' => 'v1'], function() {
    //user
    Route::post('auth/login', [LoginController::class, 'login']);
    Route::post('auth/register', [RegisterController::class, 'register']);
    // logged user
    Route::middleware('auth:api')->group(function () {
        Route::get('user/logout', [LogoutController::class, 'index']);
        Route::get('user/me', [UserController::class, 'index'])->middleware("verify_email");
    });

    // verified email
    Route::get('email/verify/{token}', [VerificationController::class, 'verifyAccount'])->name('verification.verify');
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    // Reset password
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
});
