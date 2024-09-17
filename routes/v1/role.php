<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\User\Controllers\RoleController;

Route::put('/{id}', [RoleController::class, 'update'])->name('role.update');
Route::resource('', RoleController::class);
