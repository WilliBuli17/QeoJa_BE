<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductHistoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\BankPaymentController;
use App\Http\Controllers\Api\TransactionStatusController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\DetailTransactionController;
use App\Http\Controllers\Api\ExpeditionTruckController;
use App\Http\Controllers\Api\TransactionShippingController;
use App\Http\Controllers\Api\LaporanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::get('/supplierCustomer', [SupplierController::class, 'index']);
Route::get('/categoryCustomer', [CategoryController::class, 'index']);
Route::get('/productCustomer', [ProductController::class, 'show']);
Route::post('/customerStore', [CustomerController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('/role', RoleController::class)->except(['create', 'edit', 'show']);

    Route::resource('/employee', EmployeeController::class)->except(['create', 'edit', 'update']);
    Route::post('/employee/{id}', [EmployeeController::class, 'update']);

    Route::resource('/customer', CustomerController::class)->except(['create', 'edit', 'update']);
    Route::post('/customer/{id}', [CustomerController::class, 'update']);

    Route::resource('/city', CityController::class)->except(['create', 'edit', 'show']);

    Route::resource('/address', AddressController::class)->except(['index', 'show', 'create', 'edit']);
    Route::get('/address/{id}', [AddressController::class, 'index']);

    Route::resource('/category', CategoryController::class)->except(['create', 'edit', 'show']);

    Route::resource('/supplier', SupplierController::class)->except(['create', 'edit', 'show']);

    Route::resource('/product', ProductController::class)->except(['create', 'edit', 'update', 'show']);
    Route::post('/product/{id}', [ProductController::class, 'update']);

    Route::resource('/productHistory', ProductHistoryController::class)->except(['create', 'edit', 'destroy', 'show']);
    Route::put('/productHistoryDel/{id}', [ProductHistoryController::class, 'destroy']);

    Route::resource('/cart', CartController::class)->except(['index', 'show', 'create', 'edit', 'update']);
    Route::get('/cart/{id}', [CartController::class, 'index']);
    Route::delete('/cartReset/{id}', [CartController::class, 'destroyMultiple']);

    Route::resource('/bankPayment', BankPaymentController::class)->except(['create', 'edit']);

    Route::resource('/transactionStatus', TransactionStatusController::class)->except(['create', 'edit', 'show']);

    Route::resource('/transaction', TransactionController::class)->except(['create', 'edit', 'destroy', 'update']);
    Route::post('/transaction/{id}', [TransactionController::class, 'update']);

    Route::resource('/detailTransaction', DetailTransactionController::class)->except(['create', 'edit', 'index', 'store']);

    Route::resource('/expeditionTruck', ExpeditionTruckController::class)->except(['create', 'edit', 'update', 'show']);
    Route::post('/expeditionTruck/{id}', [ExpeditionTruckController::class, 'update']);

    Route::resource('/transactionShipping', TransactionShippingController::class)->except(['create', 'edit', 'show']);

    Route::get('/laporanStockBulanan/{id}/{year}', [LaporanController::class, 'laporanStockBulanan']);
    Route::get('/laporanPendapatanBulanan/{year}', [LaporanController::class, 'laporanPendapatanBulanan']);
    Route::get('/laporanPenjualanBulanan/{id}/{year}', [LaporanController::class, 'laporanPenjualanBulanan']);
    Route::get('/laporanStockTahunan/{id}/{yearStart}/{yearEnd}', [LaporanController::class, 'laporanStockTahunan']);
    Route::get('/laporanPendapatanTahunan/{yearStart}/{yearEnd}', [LaporanController::class, 'laporanPendapatanTahunan']);
    Route::get('/laporanPenjualanTahunan/{id}/{yearStart}/{yearEnd}', [LaporanController::class, 'laporanPenjualanTahunan']);

    Route::get('/dashboardProdukTerjual/{year}', [LaporanController::class, 'dashboardProdukTerjual']);
    Route::get('/dashboardJumlahTransaksi/{year}', [LaporanController::class, 'dashboardJumlahTransaksi']);
});
