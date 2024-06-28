<?php

use App\Http\Controllers\OperatController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {return view('login');});

Route::middleware(['guest'])->group(function(){
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});

Route::get('/home', function() {return redirect('/userpermissions');});

Route::middleware(['auth'])->group(function(){
    Route::get('/userpermissions', [SesiController::class, 'terobos']);
    Route::get('/user/admin', [UserController::class, 'admin']);
    Route::get('/user/operator/dashboard', [OperatController::class, 'showdashboard']);
    Route::get('/logout', [SesiController::class, 'logout']);

    //Route operator
    //kesekretariatan
    Route::get('/user/operator/kesekretariatan', [OperatController::class, 'showKesekretariatan']);
    //Data Penduduk
    Route::get('/user/operator/datapenduduk', [OperatController::class, 'showPenduduk']);

});

