<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\EncryptionController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authorize;
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

    Route::middleware('auth')->group(function(){
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/password/change', 'edit')->name('password.change');
        Route::post('/password/change', 'update')->name('password.change');
    });
});

Route::middleware('auth')->group(function(){
    Route::controller(UserController::class)->group(function(){
        Route::get('/profile', 'index')->name('profile');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
    });
    
    Route::controller(BiodataController::class)->group(function(){
        Route::get('/biodata', 'index')->name('biodata');
        Route::get('/biodata/show', 'show')->name('biodata.show');
        Route::post('/biodata', 'create')->name('biodata');
        Route::get('/biodata/edit', 'edit')->name('biodata.edit');
        Route::put('/biodata', 'update')->name('biodata.update');
    });
    
    Route::controller(FileController::class)->group(function(){
        Route::get('/home', 'index')->name('home');
        Route::get('/file/add', 'form')->name('file.add');
        Route::post('/file', 'create')->name('file.save');

        Route::middleware(Authorize::class)->group(function(){
            Route::get('/file/{id}', 'show')->name('file.show');
            Route::get('/file/{id}/download', 'download')->name('file.download');
            Route::delete('/file/{id}', 'destroy')->name('file.delete');
        });
    });
    
    Route::controller(EncryptionController::class)->group(function(){
        Route::get('/encryption/set', 'index')->name('encryption.set');
        Route::patch('/encryption/set', 'update')->name('encryption.set');
    });

    //Pool Controller filled with file from every user, can only be opened by inserting assymetric key
    Route::controller(PoolController::class)->group(function(){
        Route::get('/pool', 'showFiles')->name('pool.index');
    });
    
});

