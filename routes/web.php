<?php

use App\Http\Controllers\OperatController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\SuratController;
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
    Route::get('/hapussurat/{id}', [SuratController::class, 'deleteSurat']);
    Route::get('/cetaksurat/{id}', [SuratController::class, 'cetakSurat']);
    //Data Penduduk
    Route::get('/user/operator/datapenduduk', [OperatController::class, 'showPenduduk'])->name('showPenduduk');
    Route::post('/editpenduduk/{NIK}', [OperatController::class, 'editpenduduk'])->name('editpenduduk');
    Route::get('/user/operator/show_tambah_penduduk', [OperatController::class, 'showAddPenduduk']);
    Route::post('/user/operator/addpenduduk', [OperatController::class, 'addPenduduk']);
    Route::get('/deletependuduk/{NIK}', [OperatController::class, 'deletePenduduk']);
    //Buat Surat
    Route::get('/user/operator/buatsurat', [OperatController::class, 'showbuatsurat']);

    //show form surat
    //SKD
    Route::get('/buatformSKD', [SuratController::class, 'showSKD']);
    Route::post('/submit_domisili', [SuratController::class, 'submitDomisili'])->name('submit_domisili');
    Route::get('/showeditsurat/{id}', [SuratController::class, 'showeditSKD']);
    Route::put('update_surat/{id}', [SuratController::class, 'update'])->name('update_domisili');

    //isiform penduduk
    Route::get('searchPemohon', [SuratController::class, 'Caripenduduk'])->name('Caripemohon');
    Route::get('showdatapenduduk/{NIK}', [SuratController::class, 'showdatapenduduk'])->name('showdatapenduduk');

    //cetak surat
    Route::post('/user/operator/submit_domisili', [OperatController::class, 'suratdomisili']);


});

