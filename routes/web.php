<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\TicketUserController;

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
    Route::get('/admin/dashboard/getData', [DashboardController::class, 'getData'])->name('admin.dashboard.getdata');
    Route::get('/admin/dashboard/getDatatable', [DashboardController::class, 'getDatatable'])->name('admin.dashboard.getdatatable');

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
    Route::get('/admin/concert/getDataTicket', [ConcertController::class, 'getDataTicket'])->name('admin.concert.getdataticket');
});

Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/user/dashboard', [DashboardUserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/dashboard/getData', [DashboardUserController::class, 'getData'])->name('user.dashboard.getdata');

    Route::get('/user/tickets', [TicketUserController::class, 'index'])->name('user.tickets');
    Route::post('/user/tickets/getData', [TicketUserController::class, 'getData'])->name('user.tickets.getdata');
    Route::post('/user/tickets/checkTicket', [TicketUserController::class, 'checkTicket'])->name('user.tickets.checkTicket');
    Route::post('/user/tickets/process', [TicketUserController::class, 'process'])->name('user.tickets.process');
    Route::get('/user/tickets/history', [TicketUserController::class, 'history'])->name('user.tickets.history');
    Route::post('/user/tickets/getDataHistory', [TicketUserController::class, 'getDataHistory'])->name('user.tickets.getdatahistory');
    Route::post('/user/tickets/download', [TicketUserController::class, 'download'])->name('user.tickets.download');

    Route::get('/user/categories/list', [CategoryController::class, 'list'])->name('user.categories.list');
});