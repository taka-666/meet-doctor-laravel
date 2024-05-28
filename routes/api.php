<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DoctorApiController;

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

Route::get('/doctors', [DoctorApiController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/doctors', [DoctorApiController::class, 'store']);
    Route::get('/doctors/{id}', [DoctorApiController::class, 'show']);
    Route::put('/doctors/{id}', [DoctorApiController::class, 'update']);
    Route::delete('/doctors/{id}', [DoctorApiController::class, 'destroy']);
});
