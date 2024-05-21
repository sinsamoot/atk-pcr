<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('activity')->group(function (){
    Route::get('/', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/create', [ActivityController::class, 'create'])->name('activity.create');
    Route::post('/store', [ActivityController::class, 'store'])->name('activity.store');
    Route::get('/destroy/{id}', [ActivityController::class, 'destroy'])->name('activity.destroy');
    Route::get('/view/{id}', [ActivityController::class, 'show'])->name('activity.show');
    Route::post('/update/{id}', [ActivityController::class, 'update'])->name('activity.update');
});

Route::prefix('regist')->group(function (){
    Route::get('/', [RegisterController::class, 'index'])->name('register.index');
    Route::get('/create', [RegisterController::class, 'create'])->name('register.create');
    // Route::get('/exam', [RegisterController::class, 'exam'])->name('register.exam');
    Route::post('/store', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/destroy/{id}', [RegisterController::class, 'destroy'])->name('register.destroy');
    Route::get('/view/{id}', [RegisterController::class, 'show'])->name('register.show');
    Route::get('/showexam/{id}', [RegisterController::class, 'showexam'])->name('register.showexam');
    Route::post('/update/{id}', [RegisterController::class, 'update'])->name('register.update');
});

Route::prefix('user')->group(function (){
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    // Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/view/{id}', [UserController::class, 'show'])->name('user.show');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/reset/{id}', [UserController::class, 'reset'])->name('user.reset');
});

require __DIR__.'/auth.php';
