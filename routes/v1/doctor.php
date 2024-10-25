<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Doctor\Controllers\DoctorController;


/**
 * Device Vendor's APIs
 **/
Route::group(['prefix' => 'specialization'], static function (): void {
    Route::get('/', [DoctorController::class, 'doctorSpecializations'])->name('doctor.specialization.index'); // List all doctor specializations
    Route::post('/', [DoctorController::class, 'specializationStore'])->name('doctor.specialization.store'); // Create a new doctor specializations
    Route::get('/{id}', [DoctorController::class, 'specializationShow'])->name('doctor.specialization.show'); // Show a specific doctor specialization
    Route::put('/{id}', [DoctorController::class, 'specializationUpdate'])->name('doctor.specialization.update'); // Update a specific doctor specialization
    Route::delete('/{id}', [DoctorController::class, 'specializationDestroy'])->name('doctor.specialization.destroy'); // Delete a specific doctor specialization
});



Route::get('/', [DoctorController::class, 'index'])->name('doctor.index'); // List all doctors
Route::post('/', [DoctorController::class, 'store'])->name('doctor.store'); // Create a new doctor
Route::get('/{id}', [DoctorController::class, 'show'])->name('doctor.show'); // Show a specific doctor
Route::put('/{id}', [DoctorController::class, 'update'])->name('doctor.update'); // Update a specific doctor
Route::delete('/{id}', [DoctorController::class, 'destroy'])->name('doctor.destroy'); // Delete a specific doctor

