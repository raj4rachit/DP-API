<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Device\Controllers\DeviceController;
use Modules\V1\Device\Controllers\DeviceVendorController;

Route::get('/', [DeviceController::class, 'index'])->name('device.index'); // List all devices
Route::post('/', [DeviceController::class, 'store'])->name('device.store'); // Create a new device
//Route::get('/{id}', [DeviceController::class, 'show'])->name('patient.show'); // Show a specific patient
//Route::put('/{id}', [DeviceController::class, 'update'])->name('patient.update'); // Update a specific patient
//Route::delete('/{id}', [DeviceController::class, 'destroy'])->name('patient.destroy'); // Delete a specific patient




Route::get('/vendor/', [DeviceVendorController::class, 'index'])->name('device.vendor.index'); // List all device vendors
Route::post('/vendor/', [DeviceVendorController::class, 'store'])->name('device.vendor.store'); // Create a new device vendor
