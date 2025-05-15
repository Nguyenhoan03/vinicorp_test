<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ManagerAssetController;
use App\Http\Controllers\ManagerDecentralizationController;
use App\Http\Middleware\VerifyAccountLogin;
use App\Http\Controllers\ManagerEmployeeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('index');
});
Route::get('/email/verify', function () {
    return view('emails.verify-email');
})->middleware('auth')->name('verification.notice');

// Xác thực email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Gửi lại email xác thực
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Đã gửi lại email xác thực!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/register', [AuthController::class, 'view_register'])->name('view_register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'view_login'])->name('view_login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => /* ['auth', 'verified' , */ VerifyAccountLogin::class], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::put('/manager_employee/{id}/role', [ManagerEmployeeController::class, 'updateRole'])->name('employees.updateRole');
    Route::get('/manager_employee', [ManagerEmployeeController::class, 'index'])->name('employees.index');
    Route::post('/create_manager_employee', [ManagerEmployeeController::class, 'create'])->name('employees.create');
    Route::put('/employees/{id}', [ManagerEmployeeController::class, 'edit'])->name('employees.edit');
    Route::delete('/delete_manager_employee', [ManagerEmployeeController::class, 'delete'])->name('employees.delete');


    Route::get('/manager_asset', [ManagerAssetController::class, 'index'])->name('assets.index');
    Route::post('/create_manager_asset', [ManagerAssetController::class, 'create'])->name('assets.create');
    Route::put('/edit_manager_asset', [ManagerAssetController::class, 'edit'])->name('assets.edit');
    Route::delete('/delete_manager_asset', [ManagerAssetController::class, 'delete'])->name('assets.delete');


    Route::get('/manager_decentralization', [ManagerDecentralizationController::class, 'index'])->name('decentralization.index');
    Route::post('/create_manager_decentralization', [ManagerDecentralizationController::class, 'create'])->name('decentralization.create');
    Route::put('/edit_manager_decentralization', [ManagerDecentralizationController::class, 'edit'])->name('decentralization.edit');
    Route::delete('/delete_manager_decentralization', [ManagerDecentralizationController::class, 'delete'])->name('decentralization.delete');

    Route::get('/export_excel', [ExcelController::class, 'export'])->name('export_excel');
    Route::post('/import_excel', [ExcelController::class, 'import'])->name('import_excel');


    Route::get('/profile_view', [AuthController::class, 'profileView'])->name('profile.view');
    Route::put('/profile_update/{id}', [AuthController::class, 'update'])->name('profile.update');

    Route::get('/profile_list_equiqment', [AuthController::class, 'list_equiqment'])->name('profile.list_equiqment');
    Route::put('/profile_update_devce', [AuthController::class, 'update_device'])->name('profile.update_device');
});
