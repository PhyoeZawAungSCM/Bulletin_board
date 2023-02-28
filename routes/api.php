<?php

use App\Http\Controllers\API\Post\PostController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\UserController;
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
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/posts', [PostController::class, 'index'])->name('postsList');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('post');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgorPassword');
Route::post('/check-token', [AuthController::class, 'checkToken'])->name('checkToken');
ROute::post('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

Route::middleware('auth:sanctum')->group(function () {
	Route::middleware(['ability:admin,user'])->group(function () {
		Route::post('/upload-csv', [PostController::class, 'uploadCsv']);
		Route::get('/download-posts', [PostController::class, 'downloadCsv']);
		Route::put('/posts/{post}', [PostController::class, 'update']);
		Route::delete('/posts/{post}', [PostController::class, 'destroy']);
		Route::post('/posts', [PostController::class, 'store']);
	});
	Route::apiResource('/users', UserController::class)->middleware(['ability:user,admin']);
	Route::get('/profile', [AuthController::class, 'profile']);
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::put('/update/{user}', [AuthController::class, 'update']);
	Route::post('/change-password', [AuthController::class, 'changePassword']);
});
