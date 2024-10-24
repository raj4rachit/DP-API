<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Version 1']);
});

/**
 * User Routes
 */
Route::middleware(['auth:sanctum'])->prefix('user')->as('user:')->group(
    base_path('routes/v1/user.php'),
);

/**
 * Authentication Routes
 */
Route::as('auth:')->group(
    base_path('routes/v1/auth.php'),
);


/**
 * Role Routes
 */
Route::middleware(['auth:sanctum'])->prefix('role')->as('role:')->group(
    base_path('routes/v1/role.php'),
);

/**
 * Permission Routes
 */
Route::middleware(['auth:sanctum'])->prefix('permission')->as('permission:')->group(
    base_path('routes/v1/permission.php'),
);

/**
 * Device Routes
 */
Route::middleware(['auth:sanctum'])->prefix('device')->as('device:')->group(
    base_path('routes/v1/device.php'),
);

/**
 * patient Routes
 */
Route::middleware(['auth:sanctum'])->prefix('patient')->as('patient:')->group(
    base_path('routes/v1/patient.php'),
);


/**
 * Hospital Routes
 */
Route::middleware(['auth:sanctum'])->prefix('hospital')->as('hospital:')->group(
    base_path('routes/v1/hospital.php'),
);
