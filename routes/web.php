<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/',[DoctorController::class,'index'])->name('doctor.index');
Route::post('/doctor/store',[DoctorController::class,'store'])->name('doctor.store');
Route::get('/download/banner/{id}', [AdminController::class, 'downloadBanner'])->name('download.banner');

Route::prefix('admin')->group(function () {
    Route::get('/login',  [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout',[AdminController::class, 'logout'])->name('admin.logout');
});
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',       [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctor',         [AdminController::class, 'doctor'])->name('doctor.index');
    Route::get('/doctor/export',  [AdminController::class, 'doctor_export'])->name('doctor.export');
    Route::post('doctor/destroy/{id}', [AdminController::class, 'doctor_destroy'])->name('doctor.destroy');
});
