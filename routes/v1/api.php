<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\V1\Hospital\Controllers\HospitalController;
use Modules\V1\Report\Controllers\ReportController;

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
 * Doctor Routes
 */
Route::middleware(['auth:sanctum'])->prefix('doctor')->as('doctor:')->group(
    base_path('routes/v1/doctor.php'),
);


/**
 * Hospital Routes
 */

Route::get('/hospital/', [HospitalController::class, 'index'])->name('hospital.index'); // List all hospitals
Route::middleware(['auth:sanctum'])->prefix('hospital')->as('hospital:')->group(
    base_path('routes/v1/hospital.php'),
);

/**
 * Report Routes
 */
Route::get('/report/', [ReportController::class, 'index'])->name('report.index'); // List all reports
Route::middleware(['auth:sanctum'])->prefix('report')->as('report:')->group(
    base_path('routes/v1/report.php'),
);

/**
 * Lab Routes
 */
Route::middleware(['auth:sanctum'])->prefix('lab')->as('lab:')->group(
    base_path('routes/v1/lab.php'),
);

/**
 * Package Routes
 */
Route::middleware(['auth:sanctum'])->prefix('package')->as('package:')->group(
    base_path('routes/v1/package.php'),
);
