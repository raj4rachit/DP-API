<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\User\Controllers\RoleController;

Route::get('/list', [RoleController::class, 'index'])->name('roles.list')->middleware('check.permissions:role-list|full-access');
Route::put('/{id}', [RoleController::class, 'update'])->name('roles.update')->middleware('check.permissions:role-edit|full-access');
Route::resource('', RoleController::class);

//Route::resource('roles', RoleController::class)->middleware('check.permissions:role-list|role-create|role-edit|role-delete');
