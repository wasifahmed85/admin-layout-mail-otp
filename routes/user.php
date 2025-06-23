<?php

use App\Http\Controllers\Backend\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'user.', 'middleware'=> ['auth:web'] ,'prefix' => 'user'], function () {
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
  
});
