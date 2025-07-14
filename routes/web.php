<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;

Route::get('/fix-app', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('key:generate');
    Artisan::call('migrate', ["--force" => true]);
    Artisan::call('config:cache');

    return '✔️ App fixed successfully!';
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
