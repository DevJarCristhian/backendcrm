<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Access\RolesController;
use App\Http\Controllers\Api\Access\UsersController;
use App\Http\Controllers\Api\Data\ChainController;
use App\Http\Controllers\Api\Data\InstitutionController;
use App\Http\Controllers\Api\Data\PharmacyController;
use App\Http\Controllers\Api\People\DependentController;
use App\Http\Controllers\Api\People\DoctorController;
use App\Http\Controllers\Api\People\PatientController;
use App\Http\Controllers\Api\People\VisitorController;
use App\Http\Controllers\Api\Sale\OpportunityController;
use App\Http\Controllers\Api\Sale\ProductController;
use App\Http\Controllers\Api\Setting\MaintenanceController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [AuthController::class, 'getUser']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::prefix('sale')->group(function () {
        Route::prefix('opportunity')->group(function () {
            Route::get('/', [OpportunityController::class, 'get']);
            Route::post('store', [OpportunityController::class, 'store']);
            Route::put('update/{id}', [OpportunityController::class, 'update']);
            Route::get('roles', [OpportunityController::class, 'getRoles']);
            Route::post('export', [OpportunityController::class, 'exportToExcel']);
        });
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'get']);
            Route::post('store', [ProductController::class, 'store']);
            Route::put('update/{id}', [ProductController::class, 'update']);
            Route::get('permissions', [ProductController::class, 'getPermissions']);
            Route::post('export', [ProductController::class, 'exportToExcel']);
        });
        Route::prefix('price')->group(function () {
            Route::get('/', [RolesController::class, 'get']);
            Route::post('store', [RolesController::class, 'store']);
            Route::put('update/{id}', [RolesController::class, 'update']);
            Route::get('permissions', [RolesController::class, 'getPermissions']);
            Route::post('export', [RolesController::class, 'exportToExcel']);
        });
    });

    Route::prefix('people')->group(function () {
        Route::prefix('dependent')->group(function () {
            Route::get('/', [DependentController::class, 'get']);
            Route::post('store', [DependentController::class, 'store']);
            Route::put('update/{id}', [DependentController::class, 'update']);
            Route::post('export', [DependentController::class, 'exportToExcel']);
        });
        Route::prefix('doctor')->group(function () {
            Route::get('/', [DoctorController::class, 'get']);
            Route::post('store', [DoctorController::class, 'store']);
            Route::put('update/{id}', [DoctorController::class, 'update']);
            Route::post('export', [DoctorController::class, 'exportToExcel']);
        });
        Route::prefix('visitor')->group(function () {
            Route::get('/', [VisitorController::class, 'get']);
            Route::post('store', [VisitorController::class, 'store']);
            Route::put('update/{id}', [VisitorController::class, 'update']);
            Route::post('export', [VisitorController::class, 'exportToExcel']);
        });
        Route::prefix('patient')->group(function () {
            Route::get('/', [PatientController::class, 'get']);
            Route::post('store', [PatientController::class, 'store']);
            Route::put('update/{id}', [PatientController::class, 'update']);
            Route::post('export', [PatientController::class, 'exportToExcel']);
        });
    });

    Route::prefix('data')->group(function () {
        Route::prefix('pharmacy')->group(function () {
            Route::get('/', [PharmacyController::class, 'get']);
            Route::post('store', [PharmacyController::class, 'store']);
            Route::put('update/{id}', [PharmacyController::class, 'update']);
            Route::post('export', [PharmacyController::class, 'exportToExcel']);
        });
        Route::prefix('chain')->group(function () {
            Route::get('/', [ChainController::class, 'get']);
            Route::post('store', [ChainController::class, 'store']);
            Route::put('update/{id}', [ChainController::class, 'update']);
            Route::post('export', [ChainController::class, 'exportToExcel']);
        });
        Route::prefix('institution')->group(function () {
            Route::get('/', [InstitutionController::class, 'get']);
            Route::post('store', [InstitutionController::class, 'store']);
            Route::put('update/{id}', [InstitutionController::class, 'update']);
            Route::post('export', [InstitutionController::class, 'exportToExcel']);
        });
    });

    Route::prefix('access')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UsersController::class, 'get']);
            Route::post('store', [UsersController::class, 'store']);
            Route::put('update/{id}', [UsersController::class, 'update']);
            Route::get('roles', [UsersController::class, 'getRoles']);
            Route::post('export', [UsersController::class, 'exportToExcel']);
        });
        Route::prefix('roles')->group(function () {
            Route::get('/', [RolesController::class, 'get']);
            Route::post('store', [RolesController::class, 'store']);
            Route::put('update/{id}', [RolesController::class, 'update']);
            Route::get('permissions', [RolesController::class, 'getPermissions']);
        });
    });

    Route::prefix('setting')->group(function () {
        Route::prefix('maintenance')->group(function () {
            Route::get('/', [MaintenanceController::class, 'get']);
            Route::post('store', [MaintenanceController::class, 'store']);
            Route::put('update/{id}', [MaintenanceController::class, 'update']);
            Route::get('child', [MaintenanceController::class, 'getChild']);
            Route::post('child/store', [MaintenanceController::class, 'storeChild']);
            Route::put('child/update/{id}', [MaintenanceController::class, 'updateChild']);
        });
    });
});
