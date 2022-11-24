<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitProductController;
use Illuminate\Support\Facades\Route;

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


Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::prefix('/auth')->group(function () {
    Route::post('/verify', [AuthController::class, 'verify']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::group(['middleware' => 'auth'], function () {


    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('category-product', CategoryProductController::class);
    Route::resource('unit-product', UnitProductController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('product', ProductController::class);
});
