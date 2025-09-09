<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
use App\Http\Controllers\PhongKhamController;

Route::get('/phong-kham', [PhongKhamController::class, 'index']);
Route::post('/phong-kham/{ma_phong}/goi-so-thuong', [PhongKhamController::class, 'goiSo']);
Route::get('/phong-kham/danh-sach', [PhongKhamController::class, 'danhSachPhong']);
Route::post('/phong-kham/{id}/reset', [PhongKhamController::class, 'reset']);
Route::post('/phong-kham/{ma_phong}/goi-uu-tien', [PhongKhamController::class, 'goiUuTien']);


use App\Http\Controllers\NhanBenhController;

// Lấy danh sách quầy
Route::get('/nhan-benh', [NhanBenhController::class, 'index']);

// Lấy logs mới nhất
Route::get('/nhan-benh-logs', [NhanBenhController::class, 'logs']);

// Gọi số thường / ưu tiên
Route::post('/nhan-benh/{quay}/goi-thuong', [NhanBenhController::class, 'goiThuong']);
Route::post('/nhan-benh/{quay}/goi-uutien', [NhanBenhController::class, 'goiUuTien']);

// Reset
Route::post('/nhan-benh/{quay}/reset', [NhanBenhController::class, 'reset']);

// Update phân khu
Route::post('/nhan-benh/{quay}/update-phankhu', [NhanBenhController::class, 'updatePhankhu']);
Route::delete('/nhan-benh-logs/{id}', [NhanBenhController::class, 'deleteLog']);

use App\Http\Controllers\BenhNhanController;

Route::post('/benhnhan', [BenhNhanController::class, 'store']);   // quét thẻ + cấp số
Route::get('/benhnhan', [BenhNhanController::class, 'index']);    // danh sách BN trong ngày
Route::get('/sotts', [BenhNhanController::class, 'sott']);       // lấy số hiện tại

