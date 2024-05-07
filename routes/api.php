<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Users\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'Api','middleware'=>'checkLang'], function () {

    Route::group(['namespace' => 'Users'], function () {
       Route::post('user/register',[AuthController::class,'register']);
       Route::post('user/login',[AuthController::class,'login']);
       Route::post('user/check/code',[AuthController::class,'check_code']);
       Route::get('user/getUserById/{id}',[AuthController::class,'getUserById']);
       Route::post('user/logout',[AuthController::class,'logout']);
       Route::get('user/getUserData',[AuthController::class,'getUserData'])->middleware('checkUser:user-api');
       Route::post('user/edit',[EditProfileController::class,'Editprofile'])->middleware('checkUser:user-api');
       Route::post('user/change_password',[EditProfileController::class,'change_password'])->middleware('checkUser:user-api');
       Route::post('user/password/email',  [ForgotPasswordController::class , 'user_forget']);
       Route::post('user/password/reset', [ResetPasswordController::class, 'user_code']);
       Route::post('all/users', [AuthController::class,'GetAllClients']);
       Route::post('user/otplogin',[AuthController::class,'OTPlogin']);
       Route::post('user/checkotp',[AuthController::class,'CheckCode']);
        Route::post('user/wishlist',[WishlistController::class,'wishlist'])->middleware('checkUser:user-api');
        Route::post('client/fcm/token', [AuthController::class, 'ftoken'])->middleware('checkUser:user-api');
        Route::post('client/notifications', [AuthController::class, 'all_users_notifications'])->middleware('checkUser:user-api');
       Route::post('user/getAllWishlist',[WishlistController::class,'getAllWishlist'])->middleware('checkUser:user-api');
    //    Route::post('user/login',[AuthController::class, 'GetAllClients']);
     });
    




});