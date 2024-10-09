<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\User\Controllers\UserController;

Route::get('/show', [UserController::class, 'show'])->name('users.show');
Route::get('/list', [UserController::class, 'list'])->name('users.list');
Route::put('/update/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::put('/change-password', [UserController::class, 'changePassword'])->name('users.changePassword');
