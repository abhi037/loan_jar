<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\changeStatusController;
use App\Http\Controllers\Admin\deleteController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\LoanController;
use Illuminate\Support\Facades\Route;

// in routes/web.php (temporarily)
use Illuminate\Support\Facades\Artisan;

Route::get('/run-storage-link', function () {
    Artisan::call('storage:link');
    Artisan::call('optimize:clear');
    return 'Storage link created and optimization cache cleared!';
});

Route::get('/', [AuthController::class, 'index'])->name('login');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('admin.guest')->group(function () {
        Route::get('/', [AuthController::class, 'index'])->name('login');

        Route::post('/', [AuthController::class, 'login'])->name('logindata');
        Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');

        Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');

        Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
        Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

    });


    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/chart-data', [AdminController::class, 'getChartData'])->name('chartData');
        Route::get('/adminprofile', [AdminController::class, 'adminprofile'])->name('adminprofile');
        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::post('/adminprofile', [AdminController::class, 'adminprofile'])->name('updateProfileImg');
        Route::patch('/adminprofile', [AdminController::class, 'adminprofile'])->name('updateProfile');
        Route::post('/changeStatus', [changeStatusController::class, 'changeStatus'])->name('changeStatus');
        Route::get('/changePassword', [AdminController::class, 'changePasswordShow'])->name('changePassword');
        Route::put('/changePassword', [AdminController::class, 'changePassword'])->name('changePassworddata');
        Route::delete('/deleteData', [deleteController::class, 'destroy'])->name('DeleteData');




        // designation section =============================================
        Route::resource('/role', RoleController::class)->middleware('permission:view_roles');
        Route::post('check-role-name', [RoleController::class, 'checkRoleName'])->name('checkRoleName')->middleware('permission:view_roles');

        Route::get('/rolepermissions/{id}', [RoleController::class, 'show'])->name('rolepermissions.show')->middleware('permission:view_role-permission');

        Route::post('/rolepermissions', [RoleController::class, 'rolePermissions'])->name('rolepermissions.store')->middleware('permission:add_role-permission');

        // staff section =============================================
        // Route::resource('/staff', StaffController::class)->middleware('permission:view_staffs');
        Route::resource('/users', StaffController::class)->middleware('permission:view_users');
        // Loan history custom route  
        Route::get('/loans/{loan}/history/{user}', [LoanController::class, 'history'])->name('loans.history')
            ->middleware('permission:view_loans');

        Route::get('/loan/{loan}/history/{user}', [LoanController::class, 'historyUser'])->name('loan.history')
            ->middleware('permission:view_user_dashboard');
            
        Route::post('/loans/{id}/saveHistory', [LoanController::class, 'saveHistory'])
            ->name('loans.saveHistory')
            ->middleware('permission:view_loans');
            
        Route::get('/loans/close/{id}', [LoanController::class, 'closeLoan'])->name('loans.closeloan');

        Route::get('/loan-report', [LoanController::class, 'report'])->name('loan.report');
        Route::get('/loan-report/data', [LoanController::class, 'getData'])->name('loan.report.data');

        Route::get('/loan-install-report', [LoanController::class, 'reportInstall'])->name('loan.report.install');
        Route::get('/loan-report-install/data', [LoanController::class, 'getDataInstall'])->name('loan.report.data.install');
        // Loans resource routes
        Route::resource('/loans', LoanController::class)->middleware('permission:view_loans');
        Route::get('/loan', [LoanController::class, 'view_user'])
            ->name('loan');

        // change password =================================================
        Route::get('/changePassword', [AdminController::class, 'changePasswordShow'])->name('changePassword');
        Route::put('/changePassword', [AdminController::class, 'changePassword'])->name('changePassworddata');


        // Delete Data =================================================

        Route::delete('/deleteData', [deleteController::class, 'destroy'])->name('DeleteData');
    });
});
