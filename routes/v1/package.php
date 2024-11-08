<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Package\Controllers\SubscriptionController;

/**
 * Package's APIs
 **/
Route::get('/', [SubscriptionController::class, 'index'])->name('package.index'); // List all packages
Route::post('/', [SubscriptionController::class, 'store'])->name('package.store'); // Create a new package
Route::get('/{id}', [SubscriptionController::class, 'show'])->name('package.show'); // Show a specific package
Route::put('/{id}', [SubscriptionController::class, 'update'])->name('package.update'); // Update a specific package
Route::delete('/{id}', [SubscriptionController::class, 'destroy'])->name('package.destroy'); // Delete a specific package
