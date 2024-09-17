<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\User\Controllers\UserController;

Route::get('/show', [UserController::class, 'show'])->name('users.show');

Route::resource('',UserController::class);
