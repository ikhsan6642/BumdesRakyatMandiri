<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CfdController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PamsimasController;
use Illuminate\Support\Facades\Route;

// 1. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. Protected Routes (BUMDes Rakyat Mandiri)
Route::middleware(['auth'])->group(function () {
    // Dashboard Eksekutif (Hanya untuk Ketua & Bendahara; Admin Unit langsung diarahkan ke unitnya)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Keuangan Terpadu & Akuntansi
    Route::prefix('keuangan')->name('finance.')->group(function () {
        Route::get('/transaksi', [FinanceController::class, 'transactions'])->name('transactions');
        Route::post('/transaksi/simpan', [FinanceController::class, 'storeTransaction'])->name('transactions.store');
        Route::delete('/transaksi/{id}/hapus', [FinanceController::class, 'destroyTransaction'])->name('transactions.destroy');
        Route::get('/transaksi/{id}/cetak', [FinanceController::class, 'printReceipt'])->name('transactions.print');
        Route::get('/laporan', [FinanceController::class, 'reports'])->name('reports');
    });

    // Unit Usaha 3: CFD (Diproteksi dengan unit.access:cfd)
    Route::prefix('unit/cfd')->name('cfd.')->middleware('unit.access:cfd')->group(function () {
        Route::get('/', [CfdController::class, 'index'])->name('index');
        Route::post('/event/simpan', [CfdController::class, 'storeEvent'])->name('events.store');
        Route::delete('/event/{id}/hapus', [CfdController::class, 'destroyEvent'])->name('events.destroy');
        Route::put('/event/{id}/update-biaya', [CfdController::class, 'updateEventCosts'])->name('events.update_costs');
        Route::post('/karcis/simpan', [CfdController::class, 'storeTicket'])->name('tickets.store');
        Route::post('/pedagang/simpan', [CfdController::class, 'storeVendor'])->name('vendors.store');
        Route::put('/pedagang/{id}/update', [CfdController::class, 'updateVendor'])->name('vendors.update');
        Route::delete('/pedagang/{id}/hapus', [CfdController::class, 'destroyVendor'])->name('vendors.destroy');
        Route::post('/zona/simpan', [CfdController::class, 'storeZone'])->name('zones.store');
        Route::put('/zona/{id}/update', [CfdController::class, 'updateZone'])->name('zones.update');
        Route::delete('/zona/{id}/hapus', [CfdController::class, 'destroyZone'])->name('zones.destroy');
    });

    // Unit Usaha 4: Pamsimas (Diproteksi dengan unit.access:pamsimas)
    Route::prefix('unit/pamsimas')->name('pamsimas.')->middleware('unit.access:pamsimas')->group(function () {
        Route::get('/', [PamsimasController::class, 'index'])->name('index');
        Route::post('/meteran/simpan', [PamsimasController::class, 'storeReading'])->name('readings.store');
        Route::post('/bayar/{id}', [PamsimasController::class, 'payBill'])->name('bills.pay');
        Route::get('/struk/{id}', [PamsimasController::class, 'printBill'])->name('bills.print');
        Route::post('/pelanggan/simpan', [PamsimasController::class, 'storeCustomer'])->name('customers.store');
        Route::put('/pelanggan/{id}/update', [PamsimasController::class, 'updateCustomer'])->name('customers.update');
        Route::delete('/pelanggan/{id}/hapus', [PamsimasController::class, 'destroyCustomer'])->name('customers.destroy');
        Route::post('/pemeliharaan/simpan', [PamsimasController::class, 'storeMaintenance'])->name('maintenance.store');
        Route::put('/tarif/update', [PamsimasController::class, 'updateSettings'])->name('settings.update');
    });
});
