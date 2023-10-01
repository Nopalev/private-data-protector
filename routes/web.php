<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\FileMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('login');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'create')->name('register');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/password/change', 'edit')->name('password.change');
    Route::post('/password/change', 'update')->name('password.change');
});

Route::controller(UserController::class)->group(function(){
    Route::get('/profile', 'index')->name('profile');
    Route::get('/profile/edit', 'edit')->name('profile.edit');
    Route::put('/profile', 'update')->name('profile.update');
});

Route::controller(BiodataController::class)->group(function(){
    Route::get('/biodata', 'index')->name('biodata');
    Route::post('/biodata', 'create')->name('biodata');
    Route::get('/biodata/edit', 'edit')->name('biodata.edit');
    Route::put('/biodata', 'update')->name('biodata.update');
});

Route::controller(FileController::class)->group(function(){
    Route::get('/home', 'index')->name('home');
    Route::get('/file/add', 'form')->name('file.add');
    Route::post('/file', 'create')->name('file.save');
    Route::get('/file', 'show')->name('file.show');
});
