<?php

use App\Http\Controllers\Backend\DatatableController;
use App\Http\Controllers\Backend\FileManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::post('update/sort/order', [DatatableController::class, 'updateSortOrder'])->name('update.sort.order');
Route::post('/content-image/upload', [FileManagementController::class, 'contentImageUpload'])->name('file.ci_upload');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/frontend.php';

