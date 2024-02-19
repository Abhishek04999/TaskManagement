<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', [TaskController::class, 'index']);
Route::post('/', [TaskController::class, 'signup']);
Route::post('/login', [TaskController::class, 'login']);
Route::post('/logout', [TaskController::class, 'logout'])->name('logout');


Route::group(['middleware' => 'auth.user'], function () {
    Route::get('/task_view', [TaskController::class, 'task_view'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks', [TaskController::class, 'sorting'])->name('tasks.sorting');
    Route::post('/categories', [TaskController::class,'Cat_store'])->name('categories.store');
});
