<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Lab\Controllers\LabController;

/**
 * Lab's APIs
 **/
Route::get('/', [LabController::class, 'index'])->name('lab.index'); // List all labs
Route::post('/', [LabController::class, 'store'])->name('lab.store'); // Create a new lab
Route::get('/{id}', [LabController::class, 'show'])->name('lab.show'); // Show a specific lab
Route::put('/{id}', [LabController::class, 'update'])->name('lab.update'); // Update a specific lab
Route::delete('/{id}', [LabController::class, 'destroy'])->name('lab.destroy'); // Delete a specific lab
