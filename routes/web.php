<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('switchLang');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\GreenCoffeeController;

Route::middleware(['auth'])->group(function () {
    Route::get('/green-coffee', [GreenCoffeeController::class, 'index'])->name('green-coffee.index');
    Route::post('/green-coffee/add-type', [GreenCoffeeController::class, 'storeType'])->name('green-coffee.storeType');
    Route::post('/green-coffee/add-batch', [GreenCoffeeController::class, 'storeBatch'])->name('green-coffee.storeBatch');
    Route::put('/green-coffee/update-type/{id}', [GreenCoffeeController::class, 'updateType'])->name('green-coffee.updateType');
    Route::delete('/green-coffee/delete-type/{id}', [GreenCoffeeController::class, 'destroyType'])->name('green-coffee.destroyType');
    Route::put('/green-coffee/update-batch/{id}', [GreenCoffeeController::class, 'updateBatch'])->name('green-coffee.updateBatch');
    Route::delete('/green-coffee/delete-batch/{id}', [GreenCoffeeController::class, 'destroyBatch'])->name('green-coffee.destroyBatch');
    Route::post('/green-coffee/pay', [GreenCoffeeController::class, 'storePayment'])->name('green-coffee.storePayment');

});