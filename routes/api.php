<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInformationController;
use Illuminate\Http\Request;
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

Route::post('/login' , [AuthController::class , 'login']);
Route::get('/users/{user}' , [UserController::class , 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout' , [AuthController::class , 'logout']);
    Route::get('/usersInformation' , [UserInformationController::class , 'index']);
    Route::post('/usersInformation' , [UserInformationController::class , 'store']);
    Route::get('/usersInformation/{userInformation}' , [UserInformationController::class , 'show']);
    Route::patch('/userInformation/update' , [UserInformationController::class , 'update']);
    Route::delete('/userInformation/{userInformation}' , [UserInformationController::class , 'destroy']);
    Route::get('/users' , [UserController::class , 'index']);
    Route::post('/users' , [UserController::class , 'store']);
    Route::get('/profile' , [UserController::class , 'profile']);
    Route::patch('/users/{user}' , [UserController::class , 'resetPassword']);
    Route::delete('/users/{user}' , [UserController::class , 'destroy']);
    Route::get('/is_have_record' , [UserInformationController::class , 'hasRecord']);
});


