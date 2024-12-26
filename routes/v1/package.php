<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Package\Controllers\PackageController;


/**
 * Package's APIs
 **/
Route::get('/', [PackageController::class, 'index'])->name('package.index'); // List all packages
Route::post('/', [PackageController::class, 'store'])->name('package.store'); // Create a new package
Route::get('/{id}', [PackageController::class, 'show'])->name('package.show'); // Show a specific package
Route::put('/{id}', [PackageController::class, 'update'])->name('package.update'); // Update a specific package
Route::delete('/{id}', [PackageController::class, 'destroy'])->name('package.destroy'); // Delete a specific package
