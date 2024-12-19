<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DoctorApiController;
use App\Http\Controllers\Api\LandingApiController;

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
Route::resource('/', LandingApiController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/doctors', [DoctorApiController::class, 'index']);
    Route::post('/doctors', [DoctorApiController::class, 'store']);
    Route::get('/doctors/{id}', [DoctorApiController::class, 'show']);
    Route::put('/doctors/{id}', [DoctorApiController::class, 'update']);
    Route::delete('/doctors/{id}', [DoctorApiController::class, 'destroy']);
});
