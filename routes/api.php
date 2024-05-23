<?php

use App\Http\Controllers\DashboardReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CulturalPropertyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CompanyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'active', 'role.admin:admin'])->prefix('admin')->group(function () {
    // USERS
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('admin.userprofile.edit');

    // DASHBOARD
    Route::get('/dashboard/users_count', [DashboardReportController::class, 'getUsersCount']);
    Route::get('/dashboard/region_props', [DashboardReportController::class, 'getRegionProperties']);
    Route::get('/dashboard/lgus', [DashboardReportController::class, 'getLGUs']);
    Route::get('/dashboard/props_status', [DashboardReportController::class, 'getPropertyStatus']);

    // CULTURAL PROPERTY
    Route::prefix('cultural-property')->group(function () {
        Route::post('/edit-inventory/{id}', [CulturalPropertyController::class, 'update'])->name('admin.updateInventory');
        Route::get('/edit-inventory/{id}/{formType}', [CulturalPropertyController::class, 'getCulturalProperty'])->name('admin.getInventory');
        Route::post('/upload-inventory', [CulturalPropertyController::class, 'uploadInventory'])->name('admin.importInventory');
        Route::get('/view-inventory', [CulturalPropertyController::class, 'getCulturalProperties'])->name('admin.getInventories');
        Route::post('/update-status/{id}', [CulturalPropertyController::class, 'updateStatus'])->name('admin.updateStatus');
        Route::post('/view-inventory/{id}', [CulturalPropertyController::class, 'destroy'])->name('admin.deleteInventory');
    });

    // ANNOUNCEMENT
    Route::get('/announcement', [AnnouncementController::class, 'getAnnouncements'])->name('admin.getAnnouncements');

    // COMPANY
    Route::get('/company', [CompanyController::class, 'getCompanies'])->name('admin.getCompanies');

    //all companies
    Route::get('/companies/all', [CompanyController::class, 'getAllCompanies'])->name('admin.getAllCompanies');
});




Route::middleware(['auth:sanctum', 'active', 'role.user:user'])->prefix('user')->group(function () {
    // USERS
    //    Route::get('/users', [UserController::class, 'getUsers']);
    //    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('user.userprofile.edit');

    // DASHBOARD
    Route::get('/dashboard/users_count', [DashboardReportController::class, 'getUsersCount']);
    Route::get('/dashboard/region_props', [DashboardReportController::class, 'getRegionProperties']);
    Route::get('/dashboard/lgus', [DashboardReportController::class, 'getLGUs']);
    Route::get('/dashboard/props_status', [DashboardReportController::class, 'getPropertyStatus']);

    // CULTURAL PROPERTY
    Route::prefix('cultural-property')->group(function () {
        Route::post('/edit-inventory/{id}', [CulturalPropertyController::class, 'update'])->name('user.updateInventory');
        Route::get('/edit-inventory/{id}/{formType}', [CulturalPropertyController::class, 'getCulturalProperty'])->name('user.getInventory');
        Route::post('/upload-inventory', [CulturalPropertyController::class, 'uploadInventory'])->name('user.importInventory');
        Route::get('/view-inventory', [CulturalPropertyController::class, 'getCulturalProperties'])->name('user.getInventories');
        Route::post('/view-inventory/{id}', [CulturalPropertyController::class, 'destroy'])->name('user.deleteInventory');
    });

    // ANNOUNCEMENT
    Route::get('/announcement', [AnnouncementController::class, 'getAnnouncements'])->name('user.getAnnouncements');
});
