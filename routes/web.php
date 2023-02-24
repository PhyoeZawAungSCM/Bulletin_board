<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/',function(){
//     return view('welcome');
// });
// Route::get('/reset-password/{token}', function ($token) {
//     return view('auth.reset-password', ['token' => $token]);
// })->middleware('guest')->name('password.reset');

Route::fallback(function(){
    return view('welcome');
});



// Route::get('/{vue_capture?}', function() {
//     return view('welcome');
// })->where('vue_capture', '[\/\w\.-]*');

// Route::view("/{any?}", "welcome")->where("any", ".*");
