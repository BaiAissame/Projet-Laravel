<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\ListTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WallController;
use App\Http\Controllers\TaskViewController;
use Illuminate\Support\Facades\Route;

Route::post('/post_message', [WallController::class, 'postMessage'])->name('message.post');
Route::put('/api/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/delete_message/{id}', [WallController::class, 'deleteMessage'])->name('message.delete');

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/projet/create', [ProjectController::class, 'create'])->name('tableau.create');
    Route::delete('/projet/{projet}', [ProjectController::class, 'destroy'])->name('projet.destroy');
    Route::get('/projets/create', [ProjectController::class, 'create'])->name('projet.create');
    Route::post('/projets', [ProjectController::class, 'store'])->name('projet.store');
    Route::get('/projet/{projet}', [ProjectController::class, 'show'])->name('projet.show');

    Route::get('/projet/{projet}/members', [ProjectMemberController::class, 'index'])->name('projet.members.index');
    Route::post('/projet/{projet}/members', [ProjectMemberController::class, 'addMember'])->name('projet.members.add');
    Route::delete('/projet/{projet}/members/{user}', [ProjectMemberController::class, 'removeMember'])->name('projet.members.remove');

    Route::get('/kanban', [App\Http\Controllers\KanbanController::class, 'index'])->name('kanban');


    Route::post('/listTask/create/{projet:slug}', [ListTaskController::class, 'create'])->name('listTask.create');
    Route::post('/listTask/update-order', [ListTaskController::class, 'updateOrder'])->name('listTask.updateOrder');
    Route::post('/listTask/update-title/{listTask}', [ListTaskController::class, 'updateTitle'])->name('listTask.updateTitle');
    Route::post('/listTask/change-color', [ListTaskController::class, 'changeColor'])->name('listTask.changeColor');
    Route::post('/listTask/update-color/{listTask}', [ListTaskController::class, 'updateColor'])->name('listTask.updateColor');
    Route::delete('/listTask/delete/{listTask}', [ListTaskController::class, 'delete'])->name('listTask.delete');

    Route::post('/task/create/{listTask}', [TaskController::class, 'create'])->name('task.create');
    Route::post('/task/update-order', [TaskController::class, 'updateOrder'])->name('task.updateOrder');
    Route::delete('/task/delete/{task}', [TaskController::class, 'delete'])->name('task.delete');
    Route::post('/task/join/{task}', [TaskController::class, 'join'])->name('task.join');
    Route::post('/task/leave/{task}', [TaskController::class, 'leave'])->name('task.leave');
    Route::post('/task/update-category/{task}', [TaskController::class, 'updateCategory'])->name('task.updateCategory');
    Route::post('/task/update-priority/{task}', [TaskController::class, 'updatePriority'])->name('task.updatePriority');
    Route::post('/task/update-content/{task}', [TaskController::class, 'updateTitleAndDescription'])->name('task.updateContent');


    Route::get('/projet/{projet}/tasks/list', [TaskViewController::class, 'listView'])->name('tasks.list');
    Route::get('/projet/{projet}/tasks/calendar', [TaskViewController::class, 'calendarView'])->name('tasks.calendar');

    Route::get('/task/details/{task}', [TaskController::class, 'details'])->name('task.details');
    Route::post('/task/update-due-date/{task}', [TaskController::class, 'updateDueDate'])->name('task.updateDueDate');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::prefix('api')->group(function () {
        Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('api.tasks.show');

        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');

        Route::post('/tasks/{task}/assignees', [TaskController::class, 'addAssignee'])->name('api.tasks.assignees.add');
        Route::delete('/tasks/{task}/assignees/{user}', [TaskController::class, 'removeAssignee'])->name('api.tasks.assignees.remove');

        Route::post('/tasks/{task}/comments', [TaskController::class, 'addComment'])->name('api.tasks.comments.add');
        Route::delete('/tasks/{task}/comments/{comment}', [TaskController::class, 'removeComment'])->name('api.tasks.comments.remove');

        Route::post('/tasks/{task}/tags', [TaskController::class, 'addTag'])->name('api.tasks.tags.add');
        Route::delete('/tasks/{task}/tags/{tag}', [TaskController::class, 'removeTag'])->name('api.tasks.tags.remove');
    });

    Route::get('/projects/export', [ProjectController::class, 'export'])->name('projects.export');
});

require __DIR__ . '/auth.php';
