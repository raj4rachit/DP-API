<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Patient\Controllers\PatientController;
use Modules\V1\User\Controllers\RoleController;


Route::get('/', [PatientController::class, 'index']); // List all patients
Route::post('/', [PatientController::class, 'store']); // Create a new patient
Route::get('/{id}', [PatientController::class, 'show']); // Show a specific patient
Route::put('/{id}', [PatientController::class, 'update']); // Update a specific patient
Route::delete('/{id}', [PatientController::class, 'destroy']); // Delete a specific patient

