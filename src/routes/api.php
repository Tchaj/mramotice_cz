<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\WebController;
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
    Route::group(["prefix" => "auth"], function () {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/register', [RegisterController::class, 'register']);
        Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
        Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
        // logged user
        Route::middleware('auth:api')->group(function () {
            Route::get('/logout', [LogoutController::class, 'index']);
            Route::get('/me', [UserController::class, 'index'])->middleware("verify_email");
            Route::get('/me', [UserController::class, 'index'])->middleware("verify_email");
            //avatar
            Route::group(["prefix" => "storage"], function () {
               // Route::post("/avatar", [StorageController::class, "storeAvatar"])->middleware("auth:api");
            });
        });
    });
    Route::group(["prefix" => "admin", "middleware" => ["auth:api", "admin"]], function () {
        /** /api/v1/admin/shop */
        Route::group(["prefix" => "web"], function () {
            Route::get("/info", [WebController::class, "get"]);
            Route::get("/dashboard", [WebController::class, "adminDashboard"]);
            Route::get("/languages", [WebController::class, "languages"]);
        });
    });


    // verified email
    Route::get('email/verify/{token}', [VerificationController::class, 'verifyAccount'])->name('verification.verify');
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    // Reset password
    Route::post('auth/forgotten-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('auth/forgotten-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
});
