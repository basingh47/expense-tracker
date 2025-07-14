<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/fix-db', function () {
    // Disable session for this route (temporary solution)
    config(['session.driver' => 'array']);

    Artisan::call('migrate', ['--force' => true]);

    return '✅ Migrations finished';
});

Route::get('/check-db', function () {
    return DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);
});

Route::get('/tables', function () {
    return DB::select('SELECT tablename FROM pg_tables WHERE schemaname = ?', ['public']);
});




Route::get('/migrate-now', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations done!';
});

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/expenses/pdf', [ExpenseController::class, 'exportPdf'])->name('expenses.pdf');
    Route::get('/expenses/send', [ExpenseController::class, 'sendPdfEmail'])->name('expenses.send');
    Route::resource('expenses', ExpenseController::class);
});


require __DIR__ . '/auth.php';
