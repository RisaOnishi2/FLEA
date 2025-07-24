<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\SellController;

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


Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/item/search', [ItemController::class, 'search'])->name('item.search');
Route::get('/item/{id}', [ItemController::class, 'detail'])->name('item.detail');
Route::middleware('auth')->group(function () {
    Route::post('/item/{id}/like', [LikeController::class, 'store'])->name('items.like');
    Route::delete('/item/{id}/unlike', [LikeController::class, 'destroy'])->name('items.unlike');
});
Route::post('/item/{id}/comments', [ItemController::class, 'storeComment'])
    ->name('item.comment')
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('purchase/{id}',[PurchaseController::class,'show'])->name('purchase.show');
    Route::post('/purchase/{id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{id}', [PurchaseController::class, 'edit'])->name('purchase.edit');
    Route::post('/purchase/address/{id}', [PurchaseController::class, 'update'])->name('purchase.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage',[MypageController::class,'index'])->name('mypage');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sell',[SellController::class,'create'])->name('sell.create');
    Route::post('/sell',[SellController::class,'store'])->name('sell.store');
});

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/profile',[ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});


