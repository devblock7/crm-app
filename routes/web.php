<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('guest');

Route::middleware(['auth'])->group(function () {

Route::get('/dashboard', function() {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::prefix('accounts')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::get('/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::get('/{account}', [AccountController::class, 'show'])->name('accounts.show');
    Route::put('/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
});

Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::get('/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::get('/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::put('/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
});

});
require __DIR__.'/auth.php';
