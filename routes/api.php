<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DoctorApiController;
use App\Http\Controllers\Api\LandingApiController;
use App\Http\Controllers\Api\ReportAppointmentApiController;
use App\Http\Controllers\Api\ReportTransactionApiController;
use App\Http\Controllers\API\AuthController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Endpoint untuk appointment dengan dokter tertentu
    Route::get('appointment/doctor/{id}', [AppointmentController::class, 'appointment'])
        ->name('appointment.doctor');
    
    // Resource route untuk appointment CRUD
    Route::apiResource('appointment', AppointmentController::class);

    //Endpoint Untuk User
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/protected', [ProtectedController::class, 'index']);

});
// Report Appointment API
Route::resource('/appointment', ReportAppointmentApiController::class);
Route::get('/appointments/export', [AppointmentReportController::class, 'export']);

// Report Transaction API
Route::resource('/transaction', TransactionReportController::class);
Route::get('/transactions/export', [TransactionReportController::class, 'export']);













// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/doctors', [DoctorApiController::class, 'index']);
//     Route::post('/doctors', [DoctorApiController::class, 'store']);
//     Route::get('/doctors/{id}', [DoctorApiController::class, 'show']);
//     Route::put('/doctors/{id}', [DoctorApiController::class, 'update']);
//     Route::delete('/doctors/{id}', [DoctorApiController::class, 'destroy']);
// });
