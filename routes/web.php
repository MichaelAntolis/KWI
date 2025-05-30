<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CostController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('kasir.index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Kasir routes
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/struk/{order}', [KasirController::class, 'show'])->name('kasir.show');

    // Cost management routes
    Route::get('/costs', [CostController::class, 'index'])->name('costs.index');
    Route::get('/costs/create', [CostController::class, 'create'])->name('costs.create');
    Route::post('/costs', [CostController::class, 'store'])->name('costs.store');
    Route::get('/costs/{cost}/edit', [CostController::class, 'edit'])->name('costs.edit');
    Route::put('/costs/{cost}', [CostController::class, 'update'])->name('costs.update');
    Route::delete('/costs/{cost}', [CostController::class, 'destroy'])->name('costs.destroy');

    // Riwayat routes
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{order}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::delete('/riwayat/{order}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    Route::post('/riwayat/reset-auto-increment', [RiwayatController::class, 'resetAutoIncrement'])->name('riwayat.reset-auto-increment');

    // Laporan routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // API routes untuk detail transaksi harian
    Route::get('/api/daily-transactions/{date}', [LaporanController::class, 'getDailyTransactions'])->name('api.daily-transactions');
});
