<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Route default ke welcome page
Route::get('/', function () {
    return view('welcome');
});

// Daftarkan middleware 'web' ke semua route
Route::middleware(['web'])->group(function () {
    // Route ke home
    Route::get('home/index', [HomeController::class, 'index']);
    Route::get('home/login', [HomeController::class, 'login']);
    Route::post('/home/post_updateMenuVisibility', [HomeController::class, 'post_updateMenuVisibility'])->name('post.updateMenuVisibility');
    Route::get('/filter-tanggal', [HomeController::class, 'filterTanggal'])->name('filter.tanggal');
    Route::post('/home/post_aksi_esetting', [HomeController::class, 'post_aksi_esetting'])->name('post.aksi_esetting');
// Route for generating PDF
Route::get('/kasus/pdf', [HomeController::class, 'kasus_pdf'])->name('kasus.pdf');
Route::get('/kasus/excel', [HomeController::class, 'kasus_excel'])->name('kasus.excel');
Route::get('/kasus/windows', [HomeController::class, 'kasus_windows'])->name('kasus.windows');
});
