<?php

use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\ApiKeyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-api-key', [ApiKeyController::class, 'getCurrentKey']);

Route::post('/product', [ProductController::class, 'apiStoreFunction'])->middleware('verify_api_key');