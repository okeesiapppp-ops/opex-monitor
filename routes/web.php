<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BacklogController;
use App\Http\Controllers\BacklogDailyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\ReconsultController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CancelController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::post('/dashboard/update-status', [DashboardController::class, 'updateStatus'])->name('dashboard.update-status');
    Route::delete('/dashboard/hapus-data', [DashboardController::class, 'hapusDataImport'])->name('dashboard.hapus-data');
    Route::post('/dashboard/update-remarks', [DashboardController::class, 'updateRemarks'])->name('dashboard.update-remarks');
    Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');

    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change-password');

    // BACKLOG ROUTES
    Route::prefix('backlog')->name('backlog.')->group(function () {
        Route::get('/', [BacklogController::class, 'index'])->name('index');
        Route::get('/create', [BacklogController::class, 'create'])->name('create');
        Route::post('/', [BacklogController::class, 'store'])->name('store');
        Route::post('/import-excel', [BacklogController::class, 'importExcel'])->name('import-excel');
        Route::get('/export-excel', [BacklogController::class, 'exportExcel'])->name('export-excel');
        Route::get('/{id}', [BacklogController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BacklogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BacklogController::class, 'update'])->name('update');
        Route::delete('/{id}', [BacklogController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/update-status', [BacklogController::class, 'updateStatus'])->name('update-status');
    });

    // BACKLOG DAILY ROUTES
    Route::prefix('backlog-daily')->name('backlog-daily.')->group(function () {
        Route::get('/', [BacklogDailyController::class, 'index'])->name('index');
        Route::get('/create', [BacklogDailyController::class, 'create'])->name('create');
        Route::post('/', [BacklogDailyController::class, 'store'])->name('store');
        Route::post('/import-excel', [BacklogDailyController::class, 'importExcel'])->name('import-excel');
        Route::get('/export-excel', [BacklogDailyController::class, 'exportExcel'])->name('export-excel');
        Route::get('/by-date-range', [BacklogDailyController::class, 'getByDateRange'])->name('by-date-range');
        Route::delete('/bulk-delete', [BacklogDailyController::class, 'bulkDelete'])->name('bulk-delete'); // TAMBAH
        Route::get('/{id}', [BacklogDailyController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BacklogDailyController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BacklogDailyController::class, 'update'])->name('update');
        Route::delete('/{id}', [BacklogDailyController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/delete', [BacklogDailyController::class, 'destroyFromCancel'])->name('delete-from-cancel');
        Route::post('/{id}/update-readiness', [BacklogDailyController::class, 'updateReadiness'])->name('update-readiness');
        Route::post('/{id}/update-eta', [BacklogDailyController::class, 'updateEta'])->name('update-eta');
        Route::post('/{id}/update-posisi', [BacklogDailyController::class, 'updatePosisi'])->name('update-posisi');
    });

    // ORDER ROUTES
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/track', [OrderController::class, 'track'])->name('track');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
    });

    // RECONCILIATION ROUTES
    Route::prefix('reconciliation')->name('reconciliation.')->group(function () {
        Route::get('/', [ReconciliationController::class, 'index'])->name('index');
        Route::get('/create', [ReconciliationController::class, 'create'])->name('create');
        Route::post('/', [ReconciliationController::class, 'store'])->name('store');
        Route::get('/export-report', [ReconciliationController::class, 'exportReport'])->name('export-report');
        Route::get('/summary', [ReconciliationController::class, 'getSummary'])->name('summary');
        Route::get('/{id}', [ReconciliationController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReconciliationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReconciliationController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReconciliationController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [ReconciliationController::class, 'approve'])->name('approve');
    });

    // RECONSULT ROUTES
    Route::prefix('reconsult')->name('reconsult.')->group(function () {
        Route::get('/', [ReconsultController::class, 'index'])->name('index');
        Route::get('/create', [ReconsultController::class, 'create'])->name('create');
        Route::post('/', [ReconsultController::class, 'store'])->name('store');
        Route::get('/resolution-time-report', [ReconsultController::class, 'resolutionTimeReport'])->name('resolution-time-report');
        Route::get('/{id}', [ReconsultController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReconsultController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReconsultController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReconsultController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/update-status', [ReconsultController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/assign-pic', [ReconsultController::class, 'assignPic'])->name('assign-pic');
    });

    // ARCHIVE ROUTES
    Route::prefix('archive')->name('archive.')->group(function () {
        Route::get('/', [ArchiveController::class, 'index'])->name('index');
        Route::get('/create', [ArchiveController::class, 'create'])->name('create');
        Route::post('/', [ArchiveController::class, 'store'])->name('store');
        Route::post('/auto-generate', [ArchiveController::class, 'autoGenerate'])->name('auto-generate');
        Route::get('/compare', [ArchiveController::class, 'compare'])->name('compare');
        Route::get('/achievement-trend', [ArchiveController::class, 'getAchievementTrend'])->name('achievement-trend');
        Route::get('/{id}', [ArchiveController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArchiveController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArchiveController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArchiveController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/export/{format}', [ArchiveController::class, 'export'])->name('export');
    });

    // REPORT ROUTES
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/backlog-achievement', [ReportController::class, 'backlogAchievement'])->name('backlog-achievement');
        Route::get('/vendor-performance', [ReportController::class, 'vendorPerformance'])->name('vendor-performance');
        Route::get('/part-deviation', [ReportController::class, 'partDeviation'])->name('part-deviation');
        Route::get('/monthly-trend', [ReportController::class, 'monthlyTrend'])->name('monthly-trend');
        Route::get('/order-status', [ReportController::class, 'orderStatus'])->name('order-status');
        Route::get('/daily-backlog-position', [ReportController::class, 'dailyBacklogPosition'])->name('daily-backlog-position');
        Route::get('/reconciliation-summary', [ReportController::class, 'reconciliationSummary'])->name('reconciliation-summary');
        Route::get('/custom-report', [ReportController::class, 'customReport'])->name('custom-report');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });

    // CANCEL ROUTES
    Route::prefix('cancel')->name('cancel.')->group(function () {
        Route::get('/', [CancelController::class, 'index'])->name('index');
        Route::post('/{id}/cancel', [CancelController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/restore', [CancelController::class, 'restore'])->name('restore');
    });

    // ADMIN ONLY ROUTES
    Route::middleware('admin')->group(function () {

        Route::prefix('vendor')->name('vendor.')->group(function () {
            Route::get('/', [VendorController::class, 'index'])->name('index');
            Route::get('/create', [VendorController::class, 'create'])->name('create');
            Route::post('/', [VendorController::class, 'store'])->name('store');
            Route::get('/{id}', [VendorController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [VendorController::class, 'edit'])->name('edit');
            Route::put('/{id}', [VendorController::class, 'update'])->name('update');
            Route::delete('/{id}', [VendorController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/update-status', [VendorController::class, 'updateStatus'])->name('update-status');
            Route::get('/{id}/performance', [VendorController::class, 'performance'])->name('performance');
        });

        Route::prefix('planner')->name('planner.')->group(function () {
            Route::get('/', [PlannerController::class, 'index'])->name('index');
            Route::get('/create', [PlannerController::class, 'create'])->name('create');
            Route::post('/', [PlannerController::class, 'store'])->name('store');
            Route::get('/{id}', [PlannerController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PlannerController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PlannerController::class, 'update'])->name('update');
            Route::delete('/{id}', [PlannerController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/update-status', [PlannerController::class, 'updateStatus'])->name('update-status');
            Route::get('/{id}/activity', [PlannerController::class, 'activity'])->name('activity');
        });

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/update-status', [UserController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
            Route::get('/{id}/activity-log', [UserController::class, 'activityLog'])->name('activity-log');
        });
    });
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});