<?php

    use App\Http\Controllers\Api\AdminAuthController;
    use App\Http\Controllers\Api\ChatController;
    use App\Http\Controllers\Api\DoctorAuthController;
    use App\Http\Controllers\Api\PatientAuthController;
    use Illuminate\Support\Facades\Route;

    Route::group(['prefix' => 'admin'], function () {
        // مسارات المصادقة
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('register', [AdminAuthController::class, 'register']);
    });

    Route::group(['prefix' => 'doctor', 'middleware' => 'RedirectIfAuthenticated:admin'], function () {
        // مسارات المصادقة
        Route::post('login', [DoctorAuthController::class, 'login']);
        Route::post('register', [DoctorAuthController::class, 'register']);


    });
    Route::group(['prefix' => 'patient', 'middleware' => 'RedirectIfAuthenticated:admin'], function () {
        // مسارات المصادقة
        Route::post('login', [PatientAuthController::class, 'login']);
        Route::post('register', [PatientAuthController::class, 'register']);

    });


    Route::group(['middleware' => 'RedirectIfAuthenticated:doctor', 'prefix' => 'doctor'], function () {

        Route::get('index', [DoctorAuthController::class, 'index']);
        Route::post('show{id}', [DoctorAuthController::class, 'show']);
        Route::post('destroy{id}', [DoctorAuthController::class, 'destroy']);
        Route::post('update{id}', [DoctorAuthController::class, 'update']);

    });

    Route::group(['middleware' => 'RedirectIfAuthenticated:doctor', 'prefix' => 'patient'], function () {

        Route::get('index', [PatientAuthController::class, 'index']);
        Route::post('show{id}', [PatientAuthController::class, 'show']);
        Route::post('destroy{id}', [PatientAuthController::class, 'destroy']);
        Route::post('update{id}', [PatientAuthController::class, 'update']);

    });


    Route::middleware('RedirectIfAuthenticated:doctor,patient')->group(function () {
        Route::post('/send-message', [ChatController::class, 'sendMessage']);
        Route::get('/messages/{doctor_id}/{patient_id}', [ChatController::class, 'fetchMessages']);
    });

    Route::middleware('RedirectIfAuthenticated:patient')->group(function () {

        Route::middleware('RedirectIfAuthenticated::patient')->group(function () {
            Route::post('appointments', [AppointmentController::class, 'store']);
        });
    });
