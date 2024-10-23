<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Device\Controllers\DeviceController;
use Modules\V1\Device\Controllers\DeviceVendorController;

/**
 * Device Vendor's APIs
 **/
Route::group(['prefix' => 'vendor'], static function (): void {
    Route::get('/', [DeviceVendorController::class, 'index'])->name('device.vendor.index'); // List all device vendors
    Route::post('/', [DeviceVendorController::class, 'store'])->name('device.vendor.store'); // Create a new device vendor
    Route::get('/{id}', [DeviceVendorController::class, 'show'])->name('device.vendor.show'); // Show a specific device vendor
    Route::put('/{id}', [DeviceVendorController::class, 'update'])->name('device.vendor.update'); // Update a specific device vendor
    Route::delete('/{id}', [DeviceVendorController::class, 'destroy'])->name('device.vendor.destroy'); // Delete a specific device vendor
});

/**
 * Device's APIs
 **/
Route::get('/', [DeviceController::class, 'index'])->name('device.index'); // List all devices
Route::post('/', [DeviceController::class, 'store'])->name('device.store'); // Create a new device
Route::get('/{id}', [DeviceController::class, 'show'])->name('device.show'); // Show a specific device
Route::put('/{id}', [DeviceController::class, 'update'])->name('device.update'); // Update a specific device
Route::delete('/{id}', [DeviceController::class, 'destroy'])->name('device.destroy'); // Delete a specific device
