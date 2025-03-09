<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot/password', [AuthController::class, 'forgetPassword'])->name('password.request');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    // Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/settings', function () { return view('dashboard'); })->name('settings');
    Route::get('/profile', function () { return view('profile'); })->name('profile');
    Route::post('/profile/update', [HomeController::class, 'uploadProfilePicture'])->name('profile.update');
    Route::resource('posts', PostController::class);
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/download-template', [PostController::class, 'downloadTemplate'])->name('template');
    Route::post('/posts/import', [PostController::class, 'importCsv'])->name('posts.import');
    Route::get('/posts/data', [PostController::class, 'getData'])->name('posts.data');
    // Route::middleware(['role:admin'])->group(function () {
    //     Route::get('/admin', function () {
    //         return "Admin Dashboard";
    //     });
    // });
});