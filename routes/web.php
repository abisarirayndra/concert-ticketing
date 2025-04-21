<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConcertController;

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

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/do-register', [AuthController::class, 'doRegister'])->name('do-register');
Route::post('/email-checking', [AuthController::class, 'emailChecking'])->name('emailChecking');
Route::post('/do-login', [AuthController::class, 'doLogin'])->name('do-login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/admin/categories/save', [CategoryController::class, 'save'])->name('admin.categories.save');
    Route::get('/admin/categories/getData', [CategoryController::class, 'getData'])->name('admin.categories.getdata');
    Route::post('/admin/categories/read', [CategoryController::class, 'read'])->name('admin.categories.read');
    Route::post('/admin/categories/destroy', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/admin/categories/list', [CategoryController::class, 'list'])->name('admin.categories.list');

    Route::get('/admin/concert', [ConcertController::class, 'index'])->name('admin.concert');
    Route::post('/admin/concert/save', [ConcertController::class, 'save'])->name('admin.concert.save');
    Route::get('/admin/concert/getData', [ConcertController::class, 'getData'])->name('admin.concert.getdata');
    Route::post('/admin/concert/read', [ConcertController::class, 'read'])->name('admin.concert.read');
    Route::post('/admin/concert/destroy', [ConcertController::class, 'destroy'])->name('admin.concert.destroy');
});
