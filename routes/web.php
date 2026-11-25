<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\MemberAuthController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MediaController;
use App\Http\Controllers\Public\ReportController;
use App\Http\Controllers\Public\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIK (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/lapor', [ReportController::class, 'create'])->name('reports.create');
Route::post('/lapor', [ReportController::class, 'store'])->middleware('throttle:5,1')->name('reports.store');
Route::get('/lapor/berhasil', [ReportController::class, 'success'])->name('reports.success');

Route::get('/tracking', TrackingController::class)->middleware('throttle:30,1')->name('tracking');

Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
Route::get('/laporan/{code}', [ReportController::class, 'show'])->name('reports.show');

// Foto laporan (disk privat), wajib signed URL.
Route::get('/media/{attachment}', MediaController::class)->middleware('signed')->name('media.show');

/*
|--------------------------------------------------------------------------
| ANGGOTA (opsional, guard "member")
|--------------------------------------------------------------------------
*/
Route::prefix('anggota')->name('member.')->group(function () {
    Route::get('/login', [MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [MemberAuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
    Route::get('/daftar', [MemberAuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [MemberAuthController::class, 'register'])->middleware('throttle:10,1')->name('register.submit');
    Route::post('/logout', [MemberAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN (guard "web" + middleware "admin")
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminLoginController::class, 'show'])->name('login');
Route::post('/login', [AdminLoginController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/{report}', [Admin\ReportController::class, 'show'])->whereNumber('report')->name('reports.show');
    Route::post('/laporan/{report}/verify', [Admin\ReportController::class, 'verify'])->whereNumber('report')->name('reports.verify');
    Route::post('/laporan/{report}/reject', [Admin\ReportController::class, 'reject'])->whereNumber('report')->name('reports.reject');
    Route::patch('/laporan/{report}/status', [Admin\ReportController::class, 'status'])->whereNumber('report')->name('reports.status');

    Route::get('/kategori', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/kategori', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/kategori/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::patch('/kategori/{category}/toggle', [Admin\CategoryController::class, 'toggle'])->name('categories.toggle');

    Route::get('/aktivitas', [Admin\ActivityLogController::class, 'index'])->name('activity.index');

    Route::get('/laporan-pdf', [Admin\ExportController::class, 'index'])->name('export.index');
    Route::get('/laporan-pdf/cetak', [Admin\ExportController::class, 'print'])->name('export.print');
});
