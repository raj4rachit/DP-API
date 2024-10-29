<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Report\Controllers\ReportController;

/**
 * Lab's APIs
 **/
Route::get('/', [ReportController::class, 'index'])->name('lab.index'); // List all labs
Route::post('/', [ReportController::class, 'store'])->name('lab.store'); // Create a new lab
Route::get('/{id}', [ReportController::class, 'show'])->name('lab.show'); // Show a specific lab
Route::put('/{id}', [ReportController::class, 'update'])->name('lab.update'); // Update a specific lab
Route::delete('/{id}', [ReportController::class, 'destroy'])->name('lab.destroy'); // Delete a specific lab
