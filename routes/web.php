<?php

use App\Http\Controllers\CulturalPropertyController;
use App\Http\Controllers\DashboardReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CompanyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware('auth:sanctum', 'active', 'role.admin:admin')->prefix('admin')->group(function () {
    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // USERS
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    // Route::post('/users', [VerifyEmail::class, 'toEmail']);
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('admin.user-profile');
    Route::put('/users/edit/{id}', [UserController::class, 'update'])->name('admin.userprofile.update');

    // CULTURAL PROPERTY
    Route::prefix('cultural-property')->group(function() {
        Route::get('/view-inventory', [CulturalPropertyController::class, 'index'])->name('admin.view.inventory');
        Route::get('/upload-inventory', [CulturalPropertyController::class, 'uploadInventoryView'])->name('admin.upload.inventory');
        Route::get('/edit-inventory/{id}', [CulturalPropertyController::class, 'edit'])->name('admin.edit.inventory');
        Route::get('/forms/{id}', [CulturalPropertyController::class, 'miscellaneousView'])->name('admin.forms');
        Route::get('/view-inventory/{id}', [CulturalPropertyController::class, 'destroy'])->name('admin.destroy.inventory');
    });

    // ANNOUNCEMENT
    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('admin.announcement');
    Route::get('/announcement/create', [AnnouncementController::class, 'create'])->name('admin.create.announcement');
    Route::post('/announcement/create', [AnnouncementController::class, 'store'])->name('admin.store.announcement');
    Route::get('/announcement/{id}/edit', [AnnouncementController::class, 'edit'])->name('admin.edit.announcement');
    Route::put('/announcement/{id}/edit', [AnnouncementController::class, 'update'])->name('admin.update.announcement');
    Route::get('/announcement/{id}/view', [AnnouncementController::class, 'view'])->name('admin.view.announcement');

    // DASHBOARD
    Route::get('/dashboard', [DashboardReportController::class, 'index'])->name('admin.dashboard');

    // COMPANY
    Route::get('/company', [CompanyController::class, 'index'])->name('admin.company');
    Route::get('/company/create', [CompanyController::class, 'create'])->name('admin.create.company');
    Route::post('/company/create', [CompanyController::class, 'store'])->name('admin.store.company');
    Route::get('/company/{id}/edit', [CompanyController::class, 'edit'])->name('admin.edit.company');
    Route::put('/company/{id}/edit', [CompanyController::class, 'update'])->name('admin.update.company');
});



Route::middleware('auth:sanctum', 'active', 'role.user:user')->prefix('user')->group(function () {

    //PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('user.profile.update');

    // USERS
    //    Route::get('/users', [UserController::class, 'index'])->name('user.users.index');
    //    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('user.user-profile');
    //    Route::put('/users/edit/{id}', [UserController::class, 'update'])->name('user.userprofile.update');

    // CULTURAL PROPERTY
    Route::prefix('cultural-property')->group(function () {
        Route::get('/view-inventory', [CulturalPropertyController::class, 'index'])->name('user.view.inventory');
        Route::get('/upload-inventory', [CulturalPropertyController::class, 'uploadInventoryView'])->name('user.upload.inventory');
        Route::get('/edit-inventory/{id}', [CulturalPropertyController::class, 'edit'])->name('user.edit.inventory');
        Route::get('/forms/{id}', [CulturalPropertyController::class, 'miscellaneousView'])->name('user.forms');
    });

    // ANNOUNCEMENT
    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('user.announcement');
    Route::get('/announcement/create', [AnnouncementController::class, 'create'])->name('user.create.announcement');
    Route::post('/announcement/create', [AnnouncementController::class, 'store'])->name('user.store.announcement');
    Route::get('/announcement/{id}/edit', [AnnouncementController::class, 'edit'])->name('user.edit.announcement');
    Route::put('/announcement/{id}/edit', [AnnouncementController::class, 'update'])->name('user.update.announcement');
    Route::get('/announcement/{id}/view', [AnnouncementController::class, 'view'])->name('user.view.announcement');

    // DASHBOARD
    Route::get('/dashboard', [DashboardReportController::class, 'index'])->name('user.dashboard');
});


require __DIR__ . '/auth.php';
