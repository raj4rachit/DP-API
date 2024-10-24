<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Hospital\Controllers\HospitalController;

/**
 * Hospital's APIs
 **/
Route::get('/', [HospitalController::class, 'index'])->name('hospital.index'); // List all hospitals
Route::post('/', [HospitalController::class, 'store'])->name('hospital.store'); // Create a new hospital
Route::get('/{id}', [HospitalController::class, 'show'])->name('hospital.show'); // Show a specific hospital
Route::put('/{id}', [HospitalController::class, 'update'])->name('hospital.update'); // Update a specific hospital
Route::delete('/{id}', [HospitalController::class, 'destroy'])->name('hospital.destroy'); // Delete a specific hospital
