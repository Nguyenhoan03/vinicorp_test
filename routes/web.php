<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagerAssetController;
use App\Http\Controllers\ManagerDecentralizationController;
use App\Http\Middleware\VerifyAccountLogin;
use App\Http\Controllers\ManagerEmployeeController;
Route::get('/', function () {
    return view('index');
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => VerifyAccountLogin::class], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/manager_employee', [ManagerEmployeeController::class, 'index'])->name('employees.index');
    Route::post('/create_manager_employee', [ManagerEmployeeController::class, 'create'])->name('employees.create');



    Route::get('/manager_asset', [ManagerAssetController::class,'index'])->name('assets.index');
    Route::get('/manager_decentralization', [ManagerDecentralizationController::class,'index'])->name('decentralization.index');
    Route::post('/create_manager_decentralization', [ManagerDecentralizationController::class,'create'])->name('decentralization.create');
    Route::put('/edit_manager_decentralization', [ManagerDecentralizationController::class,'edit'])->name('decentralization.edit');
    Route::delete('/delete_manager_decentralization', [ManagerDecentralizationController::class,'delete'])->name('decentralization.delete');
    
    Route::put('/employees/{id}/role', [ManagerEmployeeController::class, 'updateRole'])->name('employees.updateRole');

});
