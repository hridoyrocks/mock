<?php

use App\Http\Controllers\Install\InstallController;
use Illuminate\Support\Facades\Route;

Route::prefix('install')->middleware(['web', 'install'])->name('installer.')->group(function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [InstallController::class, 'permissions'])->name('permissions');
    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/database', [InstallController::class, 'databaseSave'])->name('database.save');
    Route::get('/migration', [InstallController::class, 'migration'])->name('migration');
    Route::post('/migration/run', [InstallController::class, 'runMigration'])->name('migration.run');
    Route::get('/admin', [InstallController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallController::class, 'adminSave'])->name('admin.save');
    Route::get('/final', [InstallController::class, 'final'])->name('final');
});
