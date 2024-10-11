<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\User\Controllers\PermissionController;

Route::get('/list', [PermissionController::class, 'index'])->name('permissions.list')->middleware('check.permissions:permission-list|full-access');
Route::post('/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('check.permissions:permission-create|full-access');
Route::put('/{id}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('check.permissions:permission-edit|full-access');
Route::resource('', PermissionController::class);

//Route::resource('permissions', PermissionController::class)->middleware('check.permissions:permission-list|permission-create|permission-edit|permission-delete');
