<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Report\Controllers\ReportController;

/**
 * Report's APIs
 **/
Route::get('/', [ReportController::class, 'index'])->name('report.index'); // List all reports
Route::post('/', [ReportController::class, 'store'])->name('report.store'); // Create a new report
Route::get('/{id}', [ReportController::class, 'show'])->name('report.show'); // Show a specific report
Route::put('/{id}', [ReportController::class, 'update'])->name('report.update'); // Update a specific report
Route::delete('/{id}', [ReportController::class, 'destroy'])->name('report.destroy'); // Delete a specific report
