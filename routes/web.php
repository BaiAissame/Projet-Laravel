<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TableauController;
use App\Http\Controllers\TaskController;
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
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/tableau/create', [TableauController::class, 'create'])->name('tableau.create');
    Route::delete('/projet/{projet}', [TableauController::class, 'destroy'])->name('projet.destroy');
    Route::get('/projets/create', [ProjectController::class, 'create'])->name('projet.create');
    Route::post('/projets', [ProjectController::class, 'store'])->name('projet.store');
    Route::get('/projet/{projet}', [ProjectController::class, 'show'])->name('projet.show');


    // Gestion des membres de projet
    Route::post('/projet/{projet}/members', [ProjectMemberController::class, 'addMember'])->name('projet.members.add');
    Route::delete('/projet/{projet}/members/{user}', [ProjectMemberController::class, 'removeMember'])->name('projet.members.remove');

    Route::get('/kanban', function () {
        return view('kanban.index');
    })->name('kanban');

    Route::post('/tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');

    // Routes pour les tâches
    Route::post('/task/create/{projet}', [TaskController::class, 'create'])->name('task.create');
    Route::post('/task/update-order', [TaskController::class, 'updateOrder'])->name('task.updateOrder');
});

require __DIR__ . '/auth.php';
