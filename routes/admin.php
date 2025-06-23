<?php

use App\Http\Controllers\Backend\Admin\AdminController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function () {
  Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

  // Admin Management
  Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
    // Admin Routes
    Route::resource('admin', AdminController::class);
    Route::controller(AdminController::class)->name('admin.')->prefix('admin')->group(function () {
      Route::post('/show/{admin}', 'show')->name('show');
      Route::get('/status/{admin}', 'status')->name('status');
      Route::get('/trash/bin', 'trash')->name('trash');
      Route::get('/restore/{admin}', 'restore')->name('restore');
      Route::delete('/permanent-delete/{admin}', 'permanentDelete')->name('permanent-delete');
    });
  });
});
