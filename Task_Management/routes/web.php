<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// --- Trash Bin Routes (MUST be placed BEFORE Route::resource) ---
Route::get('/tasks/trash', [TaskController::class, 'trash'])->name('tasks.trash');
Route::patch('/tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
Route::delete('/tasks/{id}/force-delete', [TaskController::class, 'forceDelete'])->name('tasks.force-delete');

// --- Standard Resource Routes ---
Route::resource('tasks', TaskController::class);

Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
    ->name('tasks.status');