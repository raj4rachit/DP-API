<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Patient\Controllers\PatientController;
use Modules\V1\User\Controllers\RoleController;


Route::get('/', [PatientController::class, 'index'])->name('patient.index'); // List all patients
Route::post('/', [PatientController::class, 'store'])->name('patient.store'); // Create a new patient
Route::get('/{id}', [PatientController::class, 'show'])->name('patient.show'); // Show a specific patient
Route::put('/{id}', [PatientController::class, 'update'])->name('patient.update'); // Update a specific patient
Route::delete('/{id}', [PatientController::class, 'destroy'])->name('patient.destroy'); // Delete a specific patient

