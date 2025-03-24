<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WallController;
use Illuminate\Support\Facades\Route;



 Route::post('/post_message', [WallController::class, 'postMessage'])->name('message.post');

 
Route::delete('/delete_message/{id}', [WallController::class, 'deleteMessage'])->name('message.delete');

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

  //route login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
//route register
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
