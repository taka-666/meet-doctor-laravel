<?php

use Illuminate\Support\Facades\Route;

// Frontsite
use App\Http\Controllers\Frontsite\AppointmentController;
use App\Http\Controllers\Frontsite\LandingController;
use App\Http\Controllers\Frontsite\PaymentController;
use App\Http\Controllers\Frontsite\RegisterController;

// Backsite
// Backsite/Menagement Access
use App\Http\Controllers\Backsite\DashboardController;
use App\Http\Controllers\Backsite\PermissionController;
use App\Http\Controllers\Backsite\RoleController;
use App\Http\Controllers\Backsite\UserController;
use App\Http\Controllers\Backsite\TypeUserController;

// Backsite/Masteer Data
use App\Http\Controllers\Backsite\ConfigPaymentController;
use App\Http\Controllers\Backsite\ConsultationController;
use App\Http\Controllers\Backsite\SpecialistController;

// Backsite/Opeartional
use App\Http\Controllers\Backsite\DoctorController;
use App\Http\Controllers\Backsite\HospitalPatientController;
use App\Http\Controllers\Backsite\ReportController;
use App\Http\Controllers\Backsite\ReportAppointmentController;
use App\Http\Controllers\Backsite\ReportTransactionController;
use App\Http\Controllers\Backsite\HospitalPatientControllerController;




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

Route::resource('/', LandingController::class);

// Frontsite
Route::group(['middleware' => ['web', 'verified']],function (){ 
        // Appointment page
        Route::get('appointment/doctor/{id}', [AppointmentController::class, 'appointment'])->name('appointment.doctor');
        Route::resource('appointment', AppointmentController::class);

        // Payment page
        Route::get('payment/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('payment/appointment/{id}', [PaymentController::class, 'payment'])->name('payment.appointment');
        Route::resource('payment', PaymentController::class);

        Route::resource('register_success', RegisterController::class);

});

// backsite nama menu
Route::group(['prefix' => 'backsite', 'as' => 'backsite.', 'middleware' => ['web', 'verified']], 
function (){ 

    // Dashboard page
    Route::resource('dashboard', DashboardController::class);

    // User Page
    Route::resource('user', UserController::class);

    // Role Page
    Route::resource('role', RoleController::class);

    // TypeUser Page
    Route::resource('type_user', TypeUserController::class);

    // Permission Page
    Route::resource('permission', PermissionController::class);
    
    // Config-Payment Page
    Route::resource('config_payment', ConfigPaymentController::class);
    
    // Consultation Page
    Route::resource('consultation', ConsultationController::class);
    
    // Specialist Page
    Route::resource('specialist', SpecialistController::class);
    
    // Appointment Backsite Page
    Route::resource('appointment', ReportAppointmentController::class);
    
    // Doctor Backsite Page
    Route::resource('doctor', DoctorController::class);
    
    // Report Backsite Page
    Route::resource('report', ReportController::class);

    // Transaction Backsite Page
    Route::resource('transaction', ReportTransactionController::class);

    // Hospital Patient Backsite Page
    Route::resource('hospital_patient', HospitalPatientController::class);
});        

