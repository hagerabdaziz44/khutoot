<?php

use App\Http\Controllers\Api\Booking\BookController;
use App\Http\Controllers\Api\Home\BusesController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Users\AuthController;
use App\Http\Controllers\Api\Users\EditProfileController;
use App\Http\Controllers\Api\Home\HomeController;
use App\Http\Controllers\Api\Home\LinesController;
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

Route::group(['namespace' => 'Api', 'middleware' => 'checkLang'], function () {

   Route::group(['namespace' => 'Users'], function () {
      Route::post('user/register', [AuthController::class, 'register']);
      Route::post('user/login', [AuthController::class, 'login']);
      Route::post('user/check/code', [AuthController::class, 'check_code']);
      Route::get('user/getUserById/{id}', [AuthController::class, 'getUserById']);
      Route::post('user/logout', [AuthController::class, 'logout']);
      Route::get('user/getUserData', [AuthController::class, 'getUserData'])->middleware('checkUser:user-api');
      Route::post('user/edit', [EditProfileController::class, 'Editprofile'])->middleware('checkUser:user-api');
      Route::post('user/change_password', [EditProfileController::class, 'change_password'])->middleware('checkUser:user-api');
      Route::post('all/users', [AuthController::class, 'GetAllClients']);
      Route::post('user/otplogin', [AuthController::class, 'OTPlogin']);
      Route::post('user/checkotp', [AuthController::class, 'CheckCode']);

      Route::post('client/notifications', [AuthController::class, 'all_users_notifications'])->middleware('checkUser:user-api');

      //    Route::post('user/login',[AuthController::class, 'GetAllClients']);
   });
   Route::group(['namespace' => 'Home'], function () {
      Route::get('home', [HomeController::class, 'home']);
      Route::post('line/by/category/id', [LinesController::class, 'get_lines_by_categoryid']);
      Route::post('buses/by/line/id', [BusesController::class, 'get_buses_by_line_id']);
      Route::post('seats/by/buses/id', [BusesController::class, 'get_seats_of_bus']);
   });
   Route::group(['namespace' => 'Booking'], function () {

      Route::post('user/book', [BookController::class, 'book'])->middleware('checkUser:user-api');
      Route::get('user/booking/list', [BookController::class, 'get_all_my_booking_list'])->middleware('checkUser:user-api');
   });
});
